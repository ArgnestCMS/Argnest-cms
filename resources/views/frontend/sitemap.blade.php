{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticUrls as $item)
    <url>
        <loc>{{ $item['url'] }}</loc>
        <changefreq>{{ $item['changefreq'] }}</changefreq>
        <priority>{{ $item['priority'] }}</priority>
    </url>
@endforeach
@foreach ($services as $service)
    <url>
        <loc>{{ route('frontend.services.show', $service) }}</loc>
        <lastmod>{{ $service->updated_at?->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
@foreach ($products as $product)
    <url>
        <loc>{{ route('frontend.products.show', $product) }}</loc>
        <lastmod>{{ $product->updated_at?->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
@foreach ($portfolios as $portfolio)
    <url>
        <loc>{{ route('frontend.references.show', $portfolio) }}</loc>
        <lastmod>{{ $portfolio->updated_at?->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
@foreach ($blogPosts as $post)
    <url>
        <loc>{{ route('frontend.blog.show', $post) }}</loc>
        <lastmod>{{ $post->updated_at?->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
</urlset>
