import os
import sys
import json
import math
import wave
import struct
import subprocess
import numpy as np

# Force UTF-8 for console output on Windows
if sys.stdout.encoding != 'utf-8':
    try:
        sys.stdout.reconfigure(encoding='utf-8')
        sys.stderr.reconfigure(encoding='utf-8')
    except Exception:
        pass

# Directory Paths
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
AUDIO_DIR = os.path.join(BASE_DIR, 'Docs', 'videos', 'audio')
VENV_TTS = os.path.join(BASE_DIR, '.venv_media', 'Scripts', 'edge-tts.exe')

if not os.path.exists(AUDIO_DIR):
    os.makedirs(AUDIO_DIR, exist_ok=True)

# 13 Official Scenes with Natural Indonesian Voice-Over Narration & Fluent English Terms
SCENES = [
    {
        "id": 1,
        "title": "Beranda Publik Klaster IKM Marmer & Onyx Tulungagung",
        "narration": "Selamat datang dalam demonstrasi Sistem I-Saplay Ceyn Menejmen Klaster IKM Kerajinan Marmer dan Oniks Kabupaten Tulungagung. Platform terpadu ini mendigitalisasi seluruh rantai pasok pengrajin batu alam mulai dari etalase produk, bahan baku, produksi Kanban, hingga Ey-Ay Forkasting."
    },
    {
        "id": 2,
        "title": "Katalog Terkurasi & Multi-Filter Toko IKM",
        "narration": "Pada katalog terkurasi publik, pembeli dapat menyaring puluhan produk kerajinan berdasarkan mitra IKM resmi, seperti UD Cahaya Oniks dan UD Putra Abadi, lengkap dengan spesifikasi teknis dan kategori batu alam."
    },
    {
        "id": 3,
        "title": "Rincian Spesifikasi Teknis & Opsi Pembelian",
        "narration": "Setiap produk, seperti Wastafel Marmer Putih B-1, menampilkan rincian spesifikasi teknis transparan, dimensi presisi, sertifikasi kilap Hay-Glosi, garansi kemasan peti kayu solid, dan harga langsung dari pengrajin."
    },
    {
        "id": 4,
        "title": "Checkout Online Fleksibel: DP 50% atau Lunas 100%",
        "narration": "Sistem menyediakan opsi pemesanan fleksibel dengan skema pembayaran uang muka De-Pe 50% untuk produk pri-order atau Pelunasan 100% dengan prioritas pengerjaan kilat melalui formulir cek-aut yang ringkas."
    },
    {
        "id": 5,
        "title": "Faktur Tagihan Digital & Payment Gateway Midtrans",
        "narration": "Setelah pesanan dibuat, pembeli langsung menerima faktur digital interaktif berstandar nasional yang terintegrasi Midtrens Peymen Getwey, mendukung pembayaran instan via Kris, Virtyual Ekaun BCA, Mandiri, dan BNI."
    },
    {
        "id": 6,
        "title": "Verifikasi Pembayaran Berhasil (Payment Gateway Success)",
        "narration": "Sistem secara otomatis memverifikasi transaksi pembayaran De-Pe, memperbarui status menjadi terverifikasi, mengunci kuota pengerjaan, dan langsung menerbitkan Surat Perintah Kerja atau Es-Pe-Ka ke lantai bengkel."
    },
    {
        "id": 7,
        "title": "Pelacakan Pesanan Real-Time (Live Tracking)",
        "narration": "Pembeli dapat melacak progres pesanan secara transparan dan ril-taym melewati 5 tahapan pengerjaan: antrean bengkel, pemotongan dan pembubutan, kendali mutu Kyu-Si dua tahap, hingga serah terima ekspedisi kargo."
    },
    {
        "id": 8,
        "title": "Autentikasi Multi-Role RBAC Petugas IKM",
        "narration": "Beralih ke panel operasional internal, sistem menerapkan proteksi Rol-Beyst Akses Kontrol ketat untuk 5 peran pengguna: Pemilik atau O-ner, Admin Penjualan, Petugas Gudang, Mandor Produksi, dan Tim Distribusi."
    },
    {
        "id": 9,
        "title": "Dashboard SCM: Micro-Tooltips Panduan Tombol",
        "narration": "Desbor eksekutif menyajikan indikator Ka-Pe-I aliran rantai pasok secara komprehensif, dilengkapi panduan maykro tultips mengambang pada setiap tombol kritis untuk memandu staf operasional di lapangan."
    },
    {
        "id": 10,
        "title": "Tur Fitur Interaktif Dashboard (Spotlight Onboarding)",
        "narration": "Bagi pengrajin baru, sistem menyediakan tur panduan fitur interaktif dengan efek spotlayt terarah tanpa bler, membimbing pengguna memahami navigasi cepat, peringatan stok, diagram 8-tahap, hingga grafik tren produksi."
    },
    {
        "id": 11,
        "title": "Manajemen Pesanan & Mekanisme 2-Gate SPK",
        "narration": "Pada modul manajemen pesanan, mekanisme Tu-Geyt Es-Pe-Ka memastikan pesanan palsu yang belum terbayar tidak mencemari bengkel. Dokumen Es-Pe-Ka resmi hanya terbit setelah pembayaran terkonfirmasi valid."
    },
    {
        "id": 12,
        "title": "Papan Kanban Penjadwalan Produksi Digital",
        "narration": "Di lantai kerja pengrajin, seluruh alur dipantau melalui papan Kanban digital 5 stasiun kerja: antrean bahan, pemotongan blok gergaji, pembubutan presisi, penghalusan poles, hingga verifikasi Kyu-Si dua tahap."
    },
    {
        "id": 13,
        "title": "Peramalan Permintaan AI ARIMA(2,0,2)",
        "narration": "Sistem dilengkapi modul Ey-Ay Dimend Forkesting berbasis algoritma Arima dua nol dua dengan tingkat akurasi tinggi dan Mape lima koma tujuh puluh tiga persen, memproyeksikan kebutuhan bahan baku marmer secara presisi untuk 3 bulan ke depan."
    }
]

def get_audio_duration(file_path):
    """Get exact duration in seconds using ffprobe"""
    try:
        cmd = [
            'ffprobe', '-v', 'error',
            '-show_entries', 'format=duration',
            '-of', 'default=noprint_wrappers=1:nokey=1',
            file_path
        ]
        result = subprocess.run(cmd, capture_output=True, text=True, check=True)
        return float(result.stdout.strip())
    except Exception as e:
        print(f"Warning: Could not get duration for {file_path}: {e}")
        return 5.0

def generate_voiceovers(voice="id-ID-GadisNeural"):
    """Generate Neural TTS voiceovers for all scenes using edge-tts"""
    print(f"🎙️ Generating AI Neural Voice-Overs using voice '{voice}'...")
    timings = {}
    
    for scene in SCENES:
        sid = scene["id"]
        out_file = os.path.join(AUDIO_DIR, f"vo_scene_{sid:02d}.mp3")
        print(f"   [Scene {sid:02d}/13] {scene['title']}...")
        
        # Call edge-tts
        cmd = [
            VENV_TTS,
            "--voice", voice,
            "--rate=+2%", # Natural conversational tempo
            "--text", scene["narration"],
            "--write-media", out_file
        ]
        subprocess.run(cmd, check=True)
        
        dur = get_audio_duration(out_file)
        timings[f"scene_{sid}"] = {
            "id": sid,
            "title": scene["title"],
            "file": out_file,
            "duration": dur
        }
        print(f"      -> Duration: {dur:.2f}s")
        
    return timings

def write_wav(filename, samples, sample_rate=44100):
    """Write float numpy array (-1.0 to 1.0) to 16-bit PCM WAV"""
    samples = np.clip(samples, -0.98, 0.98)
    int_samples = (samples * 32767).astype(np.int16)
    with wave.open(filename, 'w') as wf:
        if len(samples.shape) == 1:
            wf.setnchannels(1)
            wf.setsampwidth(2)
            wf.setframerate(sample_rate)
            wf.writeframes(int_samples.tobytes())
        else:
            wf.setnchannels(samples.shape[1])
            wf.setsampwidth(2)
            wf.setframerate(sample_rate)
            wf.writeframes(int_samples.tobytes())

def generate_sfx_assets():
    """Synthesize modern crisp SaaS sound effects"""
    print("🔊 Synthesizing Studio-Quality SaaS Sound Effects...")
    sr = 44100
    
    # 1. SFX Click / Tap (Subtle, modern UI tap 25ms)
    t_click = np.linspace(0, 0.035, int(sr * 0.035), False)
    click_wave = (
        0.7 * np.sin(2 * np.pi * 1450 * t_click) * np.exp(-t_click * 180) +
        0.3 * np.sin(2 * np.pi * 3200 * t_click) * np.exp(-t_click * 320)
    )
    click_path = os.path.join(AUDIO_DIR, "sfx_click.wav")
    write_wav(click_path, click_wave, sr)
    print("   -> Created sfx_click.wav")
    
    # 2. SFX Whoosh / Swoosh (Camera zoom & scene transition 280ms)
    dur_whoosh = 0.28
    t_whoosh = np.linspace(0, dur_whoosh, int(sr * dur_whoosh), False)
    noise = np.random.normal(0, 1, len(t_whoosh))
    # Smooth amplitude envelope (bell curve)
    env = np.sin(np.pi * t_whoosh / dur_whoosh) ** 2
    # Modulated filtered sweep
    sweep_freq = 300 + 1800 * np.sin(np.pi * t_whoosh / dur_whoosh)
    carrier = np.sin(2 * np.pi * sweep_freq * t_whoosh)
    whoosh_wave = (0.5 * noise * env + 0.5 * carrier * env) * 0.65
    whoosh_path = os.path.join(AUDIO_DIR, "sfx_whoosh.wav")
    write_wav(whoosh_path, whoosh_wave, sr)
    print("   -> Created sfx_whoosh.wav")

    # 3. SFX Pop / Tooltip (Crisp modern pop 50ms)
    dur_pop = 0.055
    t_pop = np.linspace(0, dur_pop, int(sr * dur_pop), False)
    pitch_pop = 400 + 750 * (t_pop / dur_pop)
    pop_wave = np.sin(2 * np.pi * pitch_pop * t_pop) * np.exp(-t_pop * 75) * 0.8
    pop_path = os.path.join(AUDIO_DIR, "sfx_pop.wav")
    write_wav(pop_path, pop_wave, sr)
    print("   -> Created sfx_pop.wav")

    # 4. SFX Success Chime (Double-tone harmonic chime C6 -> G6)
    dur_chime = 0.85
    t_chime = np.linspace(0, dur_chime, int(sr * dur_chime), False)
    chime_wave = np.zeros_like(t_chime)
    # Tone 1: C6 (1046 Hz) at t=0
    env1 = np.exp(-t_chime * 8)
    chime_wave += 0.45 * (np.sin(2 * np.pi * 1046.5 * t_chime) + 0.3 * np.sin(2 * np.pi * 2093 * t_chime)) * env1
    # Tone 2: G6 (1567.98 Hz) starting at t=0.12s
    t_offset = np.maximum(0, t_chime - 0.12)
    mask2 = (t_chime >= 0.12).astype(float)
    env2 = np.exp(-t_offset * 6.5) * mask2
    chime_wave += 0.55 * (np.sin(2 * np.pi * 1567.98 * t_offset) + 0.35 * np.sin(2 * np.pi * 3135.96 * t_offset)) * env2
    chime_path = os.path.join(AUDIO_DIR, "sfx_success_chime.wav")
    write_wav(chime_path, chime_wave, sr)
    print("   -> Created sfx_success_chime.wav")

    # 5. SFX Intro Swell / Cinematic Logo Reveal (5.5s Stereo Riser & Crystal Shimmer)
    dur_intro = 5.5
    t_intro = np.linspace(0, dur_intro, int(sr * dur_intro), False)
    # Pitch rises from 80Hz to 600Hz up to t=2.0s
    freq_rise = 80 * np.exp(np.minimum(t_intro, 2.0) * 0.55)
    phase_rise = 2 * np.pi * np.cumsum(freq_rise) / sr
    riser_env = np.where(t_intro <= 2.0, (t_intro / 2.0) ** 2.2, np.exp(-(t_intro - 2.0) * 4.0))
    sub_riser = np.sin(phase_rise) * riser_env
    
    # White noise sweep up to t=2.0s
    noise_intro = np.random.normal(0, 1, len(t_intro)) * riser_env * 0.25
    
    # Crystal Chime at t=2.0s with long gentle tail up to 5.2s
    t_hit = np.maximum(0, t_intro - 2.0)
    hit_mask = (t_intro >= 2.0).astype(float)
    # Fadeout smoothly towards the end
    end_fade = np.where(t_intro > 4.5, np.maximum(0, (5.5 - t_intro) / 1.0), 1.0)
    hit_env = np.exp(-t_hit * 0.8) * hit_mask * end_fade
    chime_hit = (
        0.5 * np.sin(2 * np.pi * 1318.5 * t_hit) +  # E6
        0.4 * np.sin(2 * np.pi * 1975.5 * t_hit) +  # B6
        0.3 * np.sin(2 * np.pi * 2637.0 * t_hit) +  # E7
        0.2 * np.sin(2 * np.pi * 3951.0 * t_hit)    # B7
    ) * hit_env
    
    left_intro = 0.5 * sub_riser + 0.3 * noise_intro + 0.6 * chime_hit
    right_intro = 0.5 * sub_riser + 0.3 * noise_intro + 0.6 * chime_hit
    intro_stereo = np.column_stack((left_intro, right_intro))
    intro_path = os.path.join(AUDIO_DIR, "sfx_intro_swell.wav")
    write_wav(intro_path, intro_stereo, sr)
    print("   -> Created sfx_intro_swell.wav (5.5s)")

    # 6. SFX Outro Swell / Cinematic Closing Chime (5.6s Warm Harmonic Resolution)
    dur_outro = 5.6
    t_outro = np.linspace(0, dur_outro, int(sr * dur_outro), False)
    # Warm resolving chord: Ebmaj9 (Eb3=155.56, G3=196.00, Bb3=233.08, D4=293.66, F4=349.23)
    outro_chord = [155.56, 196.00, 233.08, 293.66, 349.23]
    outro_env = np.where(t_outro < 1.2, np.sin(np.pi * t_outro / (2 * 1.2)), 1.0)
    outro_fade = np.where(t_outro > 4.0, np.maximum(0, (5.6 - t_outro) / 1.6), 1.0)
    total_env = outro_env * outro_fade
    
    left_outro = np.zeros_like(t_outro)
    right_outro = np.zeros_like(t_outro)
    for idx, f in enumerate(outro_chord):
        pan = 0.8 + 0.2 * math.cos(idx)
        tone = np.sin(2 * np.pi * f * t_outro) + 0.3 * np.sin(2 * np.pi * f * 2.0 * t_outro)
        left_outro += tone * pan * (0.15 / len(outro_chord)) * total_env
        right_outro += tone * (2.0 - pan) * (0.15 / len(outro_chord)) * total_env
        
    # Add gentle sub bass
    sub_bass = 0.2 * np.sin(2 * np.pi * 77.78 * t_outro) * total_env
    left_outro += sub_bass
    right_outro += sub_bass
    
    # Sparkle shimmer hit at t=1.0s
    t_shimmer = np.maximum(0, t_outro - 1.0)
    shimmer_mask = (t_outro >= 1.0).astype(float)
    shimmer_env = np.exp(-t_shimmer * 1.0) * shimmer_mask * outro_fade
    sparkle = (
        0.3 * np.sin(2 * np.pi * 1244.5 * t_shimmer) + # Eb6
        0.2 * np.sin(2 * np.pi * 1864.7 * t_shimmer) + # Bb6
        0.15 * np.sin(2 * np.pi * 2349.3 * t_shimmer)   # D7
    ) * shimmer_env
    left_outro += sparkle * 0.4
    right_outro += sparkle * 0.4
    
    outro_stereo = np.column_stack((left_outro, right_outro))
    peak_out = np.max(np.abs(outro_stereo))
    if peak_out > 0:
        outro_stereo = (outro_stereo / peak_out) * 0.75
    outro_path = os.path.join(AUDIO_DIR, "sfx_outro_swell.wav")
    write_wav(outro_path, outro_stereo, sr)
    print("   -> Created sfx_outro_swell.wav (5.6s)")

def generate_ambient_saas_bgm(total_duration_sec=320):
    """
    Synthesize an elegant, warm, copyright-free corporate SaaS ambient background track.
    Features:
    - Warm analog synth pad chords (Ebmaj7 - Cm7 - Abmaj7 - Bb)
    - Gentle acoustic marimba/pluck arpeggio
    - Subtle sub-bass grounding
    - Soft rhythmic pulse
    """
    print(f"🎵 Synthesizing Ambient Corporate SaaS Background Music ({total_duration_sec}s)...")
    sr = 44100
    total_samples = int(sr * total_duration_sec)
    t = np.linspace(0, total_duration_sec, total_samples, False)
    
    # Chord progression: Ebmaj7 (Eb, G, Bb, D) -> Cm7 (C, Eb, G, Bb) -> Abmaj7 (Ab, C, Eb, G) -> Bbadd9 (Bb, D, F, C)
    # Each chord lasts 8 seconds (tempo ~60-70 bpm feel)
    chord_len = 8.0
    num_chords = int(math.ceil(total_duration_sec / chord_len))
    
    chords = [
        # Ebmaj7: Eb3, G3, Bb3, D4
        [155.56, 196.00, 233.08, 293.66],
        # Cm7: C3, Eb3, G3, Bb3
        [130.81, 155.56, 196.00, 233.08],
        # Abmaj7: Ab2, C3, Eb3, G3
        [103.83, 130.81, 155.56, 196.00],
        # Bbadd9: Bb2, D3, F3, C4
        [116.54, 146.83, 174.61, 261.63],
    ]
    
    left_chan = np.zeros(total_samples, dtype=np.float32)
    right_chan = np.zeros(total_samples, dtype=np.float32)
    
    for i in range(num_chords):
        c_idx = i % len(chords)
        notes = chords[c_idx]
        start_samp = int(i * chord_len * sr)
        end_samp = min(total_samples, int((i + 1) * chord_len * sr))
        if start_samp >= total_samples:
            break
        
        chord_t = t[start_samp:end_samp] - (i * chord_len)
        cur_len = len(chord_t)
        
        # Envelope: Gentle 1.5s attack, sustain, 1.5s release
        attack_len = int(1.5 * sr)
        release_len = int(1.5 * sr)
        env = np.ones(cur_len, dtype=np.float32)
        if cur_len > attack_len:
            env[:attack_len] = np.sin(np.linspace(0, np.pi/2, attack_len)) ** 2
        if cur_len > release_len:
            env[-release_len:] = np.cos(np.linspace(0, np.pi/2, release_len)) ** 2
            
        pad_signal_l = np.zeros(cur_len, dtype=np.float32)
        pad_signal_r = np.zeros(cur_len, dtype=np.float32)
        
        # Layer notes with subtle detuning and stereo panning
        for n_i, freq in enumerate(notes):
            # Rich warm saw/sine hybrid
            harmonic1 = np.sin(2 * np.pi * freq * chord_t)
            harmonic2 = 0.4 * np.sin(2 * np.pi * (freq * 1.002) * chord_t)
            harmonic3 = 0.2 * np.sin(2 * np.pi * (freq * 2.001) * chord_t)
            voice_l = (harmonic1 + harmonic2) * (0.8 + 0.2 * math.cos(n_i))
            voice_r = (harmonic1 - harmonic2) * (0.8 + 0.2 * math.sin(n_i))
            pad_signal_l += voice_l
            pad_signal_r += voice_r
            
        # Add subtle deep root bass
        root_freq = notes[0] / 2.0
        sub_bass = 0.5 * np.sin(2 * np.pi * root_freq * chord_t) * env
        
        # Combine
        left_chan[start_samp:end_samp] += (pad_signal_l * 0.12 * env + sub_bass * 0.15)
        right_chan[start_samp:end_samp] += (pad_signal_r * 0.12 * env + sub_bass * 0.15)

    # Add gentle rhythmic high-frequency tape warmth / pulse (every 0.5s)
    pulse_t = np.linspace(0, total_duration_sec, total_samples, False)
    subtle_pulse = 0.015 * np.sin(2 * np.pi * 2.0 * pulse_t) * np.sin(2 * np.pi * 880 * pulse_t)
    left_chan += subtle_pulse
    right_chan += subtle_pulse
    
    # Master compression / normalization
    stereo_bgm = np.column_stack((left_chan, right_chan))
    peak = np.max(np.abs(stereo_bgm))
    if peak > 0:
        stereo_bgm = (stereo_bgm / peak) * 0.75
        
    bgm_path = os.path.join(AUDIO_DIR, "bgm_saas_ambient.wav")
    write_wav(bgm_path, stereo_bgm, sr)
    print(f"   -> Created bgm_saas_ambient.wav ({total_duration_sec}s)")
    return bgm_path

def main():
    print("=" * 60)
    print("🚀 E-SCM MARMER AUDIO & VOICE-OVER ASSETS ENGINE")
    print("=" * 60)
    
    # 1. Voice Overs
    vo_timings = generate_voiceovers()
    
    # 2. Sound Effects (SaaS UI)
    generate_sfx_assets()
    
    # 3. Calculate total video duration needed
    total_vo_dur = sum(item["duration"] for item in vo_timings.values())
    print(f"\n📊 Total Voice-Over Narration Duration: {total_vo_dur:.2f}s (~{total_vo_dur/60:.2f} minutes)")
    
    # 4. Generate BGM with comfortable buffer (extra 60s for intro/outro/transitions)
    bgm_dur = int(math.ceil(total_vo_dur + 90))
    bgm_path = generate_ambient_saas_bgm(bgm_dur)
    
    # 5. Save Timings & Metadata JSON
    metadata = {
        "voice": "id-ID-GadisNeural",
        "total_scenes": len(SCENES),
        "total_vo_duration": total_vo_dur,
        "scenes": vo_timings,
        "sfx": {
            "click": os.path.join(AUDIO_DIR, "sfx_click.wav"),
            "whoosh": os.path.join(AUDIO_DIR, "sfx_whoosh.wav"),
            "pop": os.path.join(AUDIO_DIR, "sfx_pop.wav"),
            "success_chime": os.path.join(AUDIO_DIR, "sfx_success_chime.wav"),
            "intro_swell": os.path.join(AUDIO_DIR, "sfx_intro_swell.wav"),
            "outro_swell": os.path.join(AUDIO_DIR, "sfx_outro_swell.wav")
        },
        "bgm": bgm_path
    }
    
    timings_json_path = os.path.join(AUDIO_DIR, "audio_timings.json")
    with open(timings_json_path, 'w', encoding='utf-8') as f:
        json.dump(metadata, f, indent=4)
        
    print(f"\n✅ All audio assets & timings saved successfully to: {timings_json_path}")

if __name__ == '__main__':
    main()
