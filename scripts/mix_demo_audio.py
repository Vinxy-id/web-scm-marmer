import os
import sys
import json
import math
import wave
import subprocess
import numpy as np

# Force UTF-8 for console output on Windows
if sys.stdout.encoding != 'utf-8':
    try:
        sys.stdout.reconfigure(encoding='utf-8')
        sys.stderr.reconfigure(encoding='utf-8')
    except Exception:
        pass

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
AUDIO_DIR = os.path.join(BASE_DIR, 'Docs', 'videos', 'audio')

SAMPLE_RATE = 44100

def load_audio(file_path):
    """Load audio file into 44.1kHz stereo float32 numpy array quickly"""
    if file_path.endswith('.wav'):
        with wave.open(file_path, 'r') as wf:
            n_channels = wf.getnchannels()
            sample_width = wf.getsampwidth()
            frames = wf.readframes(wf.getnframes())
            if sample_width == 2:
                samples = np.frombuffer(frames, dtype=np.int16).astype(np.float32) / 32767.0
            else:
                samples = np.frombuffer(frames, dtype=np.float32)
            if n_channels == 1:
                return np.column_stack((samples, samples))
            else:
                return samples.reshape(-1, n_channels)
    else:
        cmd = [
            'ffmpeg', '-y', '-i', file_path,
            '-f', 's16le', '-ac', '2', '-ar', str(SAMPLE_RATE),
            'pipe:1'
        ]
        res = subprocess.run(cmd, capture_output=True, check=True)
        samples = np.frombuffer(res.stdout, dtype=np.int16).astype(np.float32) / 32767.0
        return samples.reshape(-1, 2)

def fast_moving_average(arr, window):
    """O(N) moving average using cumsum (sub-millisecond execution)"""
    pad_w = window // 2
    padded = np.pad(arr, (pad_w, pad_w), mode='edge')
    cs = np.cumsum(padded, dtype=np.float64)
    return ((cs[window:] - cs[:-window]) / window).astype(np.float32)

def write_wav(filename, samples):
    """Write float numpy array (-1.0 to 1.0) to 16-bit PCM WAV"""
    samples = np.clip(samples, -0.98, 0.98)
    int_samples = (samples * 32767).astype(np.int16)
    with wave.open(filename, 'w') as wf:
        wf.setnchannels(2)
        wf.setsampwidth(2)
        wf.setframerate(SAMPLE_RATE)
        wf.writeframes(int_samples.tobytes())

def mix_walkthrough_audio(events_json_path, total_video_duration_sec, output_wav_path):
    print("=" * 60)
    print("🎛️ E-SCM AUDIO MIXER & DUCKING ENGINE")
    print("=" * 60)
    
    with open(events_json_path, 'r', encoding='utf-8') as f:
        events = json.load(f)
        
    print(f"📊 Total Video Duration to Match: {total_video_duration_sec:.2f}s")
    total_samples = int(math.ceil(total_video_duration_sec * SAMPLE_RATE))
    
    # Master channels
    master_vo = np.zeros((total_samples, 2), dtype=np.float32)
    master_sfx = np.zeros((total_samples, 2), dtype=np.float32)
    speech_active = np.zeros(total_samples, dtype=np.float32)
    
    # Preload SFX assets
    sfx_files = {
        'sfx_click': os.path.join(AUDIO_DIR, 'sfx_click.wav'),
        'sfx_whoosh': os.path.join(AUDIO_DIR, 'sfx_whoosh.wav'),
        'sfx_pop': os.path.join(AUDIO_DIR, 'sfx_pop.wav'),
        'sfx_success_chime': os.path.join(AUDIO_DIR, 'sfx_success_chime.wav')
    }
    loaded_sfx = {}
    for k, p in sfx_files.items():
        if os.path.exists(p):
            loaded_sfx[k] = load_audio(p)
            
    # Process Events
    print(f"📝 Processing {len(events)} timeline audio events...")
    for ev in events:
        ev_type = ev['type']
        t_sec = ev['time']
        start_samp = int(t_sec * SAMPLE_RATE)
        if start_samp >= total_samples:
            continue
            
        if ev_type == 'vo':
            scene_id = ev['scene']
            vo_path = os.path.join(AUDIO_DIR, f"vo_scene_{scene_id:02d}.mp3")
            if os.path.exists(vo_path):
                vo_data = load_audio(vo_path)
                end_samp = min(total_samples, start_samp + len(vo_data))
                cut_len = end_samp - start_samp
                # Boost VO gain for clear, prominent, upfront vocal presence
                master_vo[start_samp:end_samp] += vo_data[:cut_len] * 1.25
                # Mark speech mask with extra 0.4s buffer at the end
                buf_samp = min(total_samples, end_samp + int(0.4 * SAMPLE_RATE))
                speech_active[start_samp:buf_samp] = 1.0
                print(f"   🎙️ Scene {scene_id:02d} VO placed at t={t_sec:.2f}s (len: {len(vo_data)/SAMPLE_RATE:.2f}s)")
                
        elif ev_type in loaded_sfx:
            sfx_data = loaded_sfx[ev_type]
            # Very delicate, subtle UI sound design so SFX never masks the voice over
            vol = 0.05
            if ev_type == 'sfx_click': vol = 0.05
            elif ev_type == 'sfx_whoosh': vol = 0.04
            elif ev_type == 'sfx_pop': vol = 0.05
            elif ev_type == 'sfx_success_chime': vol = 0.08
            
            end_samp = min(total_samples, start_samp + len(sfx_data))
            cut_len = end_samp - start_samp
            master_sfx[start_samp:end_samp] += sfx_data[:cut_len] * vol
            
    # Smooth speech mask for silky natural ducking (0.5s ramp)
    print("🌊 Applying Dynamic Audio Ducking to Background Music...")
    kernel_len = int(0.5 * SAMPLE_RATE)
    smoothed_speech = fast_moving_average(speech_active, kernel_len)
    smoothed_speech = np.clip(smoothed_speech, 0.0, 1.0)
    
    # Ducking curve: 0.28 (when talking, clearly audible warm bed) to 0.58 (when quiet, full rich musical swell)
    bgm_volume_curve = 0.58 - (0.30 * smoothed_speech)
    
    # Load and loop BGM to fit total duration
    bgm_path = os.path.join(AUDIO_DIR, 'bgm_saas_ambient.wav')
    if os.path.exists(bgm_path):
        bgm_data = load_audio(bgm_path)
        # Repeat if needed
        repeats = int(math.ceil(total_samples / len(bgm_data)))
        bgm_full = np.tile(bgm_data, (repeats, 1))[:total_samples]
        
        # Apply ducking envelope
        bgm_ducked = bgm_full * bgm_volume_curve[:, None]
    else:
        bgm_ducked = np.zeros((total_samples, 2), dtype=np.float32)
        
    # Master Audio Sum
    master_mix = master_vo + master_sfx + bgm_ducked
    
    # Peak Limiter / Soft Normalization (-0.5 dB peak)
    peak = np.max(np.abs(master_mix))
    if peak > 0.95:
        master_mix = (master_mix / peak) * 0.95
        print(f"   🎚️ Master normalized from peak {peak:.2f} to 0.95")
        
    write_wav(output_wav_path, master_mix)
    print(f"✅ Master Walkthrough Audio written to: {output_wav_path}")
    return output_wav_path

if __name__ == '__main__':
    events_json = os.path.join(AUDIO_DIR, 'timeline_events.json')
    output_wav = os.path.join(AUDIO_DIR, 'master_walkthrough_audio.wav')
    # Default duration 180s if testing standalone
    dur = 180.0
    if len(sys.argv) > 1:
        dur = float(sys.argv[1])
    mix_walkthrough_audio(events_json, dur, output_wav)
