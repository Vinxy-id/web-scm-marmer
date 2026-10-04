{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Halaman Utama & Navigasi Publik -->
    <url>
        <loc>{{ $baseUrl }}/</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ $baseUrl }}/katalog</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ $baseUrl }}/lacak-pesanan</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Detail Seluruh Produk Kerajinan Marmer, Onyx & Batu Kali -->
    @foreach($products as $product)
    <url>
        <loc>{{ $baseUrl }}/katalog/{{ $product->id }}</loc>
        <lastmod>{{ ($product->updated_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
