import asyncio
import edge_tts

scenes_test = [
    {
        "id": 1,
        "text": "Selamat datang dalam demonstrasi Sistem I-Saplay Ceyn Menejmen Klaster IKM Kerajinan Marmer dan Oniks Kabupaten Tulungagung. Platform terpadu ini mendigitalisasi seluruh rantai pasok pengrajin batu alam mulai dari etalase produk, bahan baku, produksi Kanban, hingga Ey-Ay Forkasting."
    },
    {
        "id": 13,
        "text": "Sistem dilengkapi modul Ey-Ay Dimend Forkesting berbasis algoritma Arima dua nol dua dengan tingkat akurasi tinggi dan Mape lima koma tujuh puluh tiga persen, memproyeksikan kebutuhan bahan baku marmer secara presisi untuk 3 bulan ke depan."
    }
]

async def run():
    for s in scenes_test:
        out = f"Docs/videos/audio/test_voices/scene_{s['id']}_gadis_test.mp3"
        c = edge_tts.Communicate(s['text'], 'id-ID-GadisNeural', rate="+1%")
        await c.save(out)
        print(f"Scene {s['id']} generated: {out}")

if __name__ == '__main__':
    asyncio.run(run())
