import asyncio
import edge_tts
import os

async def main():
    os.makedirs('Docs/videos/audio/test_voices', exist_ok=True)
    
    # Test SSML with sub alias
    ssml_text = """<speak version="1.0" xmlns="http://www.w3.org/2001/10/synthesis" xml:lang="id-ID">
    <voice name="id-ID-GadisNeural">
        Selamat datang di Sistem <sub alias="I Saplay Cen Menejmen">E-Supply Chain Management</sub> dengan <sub alias="Ey Ay">AI</sub> Demand Forecasting.
    </voice>
</speak>"""
    try:
        c = edge_tts.Communicate(ssml_text, 'id-ID-GadisNeural')
        await c.save('Docs/videos/audio/test_voices/test_sub.mp3')
        print("SSML sub alias success!")
    except Exception as e:
        print("SSML failed:", e)

if __name__ == '__main__':
    asyncio.run(main())
