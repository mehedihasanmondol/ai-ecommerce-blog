<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Ecommerce\Product\Models\Product;
use App\Modules\Ecommerce\Category\Models\Category;
use App\Modules\Ecommerce\Brand\Models\Brand;
use App\Models\SaleOffer;
use App\Models\TrendingProduct;
use App\Models\BestSellerProduct;
use App\Models\NewArrivalProduct;
use App\Models\SiteSetting;
use App\Models\User;

/**
 * ModuleName: Home Controller
 * Purpose: Handle public homepage and landing pages
 * 
 * Key Methods:
 * - index(): Display homepage with featured products
 * 
 * Dependencies:
 * - Product Model
 * - Category Model
 * - Brand Model
 * 
 * @category Frontend
 * @package  Controllers
 * @author   Windsurf AI
 * @created  2025-01-06
 */
class HomeController extends Controller
{
    /**
     * Display the homepage
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Check homepage type setting
        $homepageType = SiteSetting::get('homepage_type', config('homepage.default_type', 'default'));

        // Handle different homepage types
        switch ($homepageType) {
            case 'author_profile':
                return $this->showAuthorHomepage();

            case 'newspaper':
                return $this->showNewspaperHomepage();

                // Future extensible types can be added here:
                // case 'category_page':
                //     return $this->showCategoryHomepage();
                // case 'blog_index':
                //     return redirect()->route('blog.index');
                // case 'custom_page':
                //     return $this->showCustomPageHomepage();

            case 'default':
            default:
                return $this->showDefaultHomepage();
        }
    }

    /**
     * Display default homepage with products
     *
     * @return \Illuminate\View\View
     */
    public function showDefaultHomepage()
    {
        // Get featured products (limit 12 for slider)
        $featuredProducts = Product::with(['variants', 'categories', 'brand', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->limit(12)
            ->get();

        // Get new arrivals (latest 8 products)
        $newArrivals = Product::with(['variants', 'categories', 'brand', 'images'])
            ->where('is_active', true)
            ->latest()
            ->limit(8)
            ->get();

        // Get best sellers (products with most orders - placeholder for now)
        $bestSellers = Product::with(['variants', 'categories', 'brand', 'images'])
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Get all parent categories (for Shop by Category section)
        $featuredCategories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        // Get featured brands (limit 12)
        $featuredBrands = Brand::where('is_active', true)
            ->where('is_featured', true)
            ->limit(12)
            ->get();

        // Get sale offers products
        $saleOffers = SaleOffer::with(['product.variants', 'product.categories', 'product.brand', 'product.images'])
            ->active()
            ->ordered()
            ->get()
            ->pluck('product')
            ->filter(fn($product) => $product && $product->is_active);

        // Get trending products
        $trendingProducts = TrendingProduct::with(['product.variants', 'product.categories', 'product.brand', 'product.images'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->pluck('product')
            ->filter(fn($product) => $product && $product->is_active);

        // Get best seller products
        $bestSellerProducts = BestSellerProduct::with(['product.variants', 'product.categories', 'product.brand', 'product.images'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->pluck('product')
            ->filter(fn($product) => $product && $product->is_active);

        // Get new arrival products
        $newArrivalProducts = NewArrivalProduct::with(['product.variants', 'product.categories', 'product.brand', 'product.images'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->pluck('product')
            ->filter(fn($product) => $product && $product->is_active);

        // Prepare SEO data for default homepage
        $siteName = SiteSetting::get('site_name', config('app.name'));
        $siteTagline = SiteSetting::get('site_tagline', '');
        $siteLogo = SiteSetting::get('site_logo');

        $seoData = [
            'title' => $siteTagline ? $siteName . ' | ' . $siteTagline : $siteName,
            'description' => SiteSetting::get('meta_description', 'Shop health, wellness and beauty products'),
            'keywords' => SiteSetting::get('meta_keywords', 'health, wellness, beauty, supplements'),
            'og_image' => $siteLogo ? asset('storage/' . $siteLogo) : asset('images/og-default.jpg'),
            'og_type' => 'website',
            'canonical' => url('/'),
        ];

        return view('frontend.home.index', compact(
            'featuredProducts',
            'newArrivals',
            'bestSellers',
            'featuredCategories',
            'featuredBrands',
            'saleOffers',
            'trendingProducts',
            'bestSellerProducts',
            'newArrivalProducts',
            'seoData'
        ));
    }

    /**
     * Display author profile as homepage
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    protected function showAuthorHomepage()
    {
        $authorId = SiteSetting::get('homepage_author_id');

        if (!$authorId) {
            // If no author is selected, fall back to default homepage
            return $this->showDefaultHomepage();
        }

        $author = User::with('authorProfile')->find($authorId);

        if (!$author || !$author->authorProfile) {
            // If author not found or has no profile, fall back to default
            return $this->showDefaultHomepage();
        }

        // Get categories for sidebar
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->withCount('activeChildren')
            ->orderBy('name')
            ->get();

        // Prepare SEO data for author profile homepage
        $authorProfile = $author->authorProfile;
        $authorProfile->load('media'); // Eager load media library image
        $jobTitle = $authorProfile->job_title ?? 'Author Profile';

        $seoData = [
            'title' => $author->name . ' | ' . $jobTitle,
            'description' => $authorProfile->bio ? \Illuminate\Support\Str::limit(strip_tags($authorProfile->bio), 160) : 'View profile and articles by ' . $author->name,
            'keywords' => $author->name . ', author, blog, articles, writer' . ($authorProfile->job_title ? ', ' . $authorProfile->job_title : ''),
            'og_image' => ($authorProfile->media && $authorProfile->media->large_url)
                ? $authorProfile->media->large_url
                : ($authorProfile->avatar
                    ? asset('storage/' . $authorProfile->avatar)
                    : asset('images/default-avatar.jpg')),
            'og_type' => 'profile',
            'canonical' => url('/'),
            'author_name' => $author->name,
        ];

        // Render author profile page as homepage
        return view('frontend.blog.author', compact('author', 'categories', 'seoData'));
    }

    /**
     * Display newspaper-style homepage
     *
     * @return \Illuminate\View\View
     */
    protected function showNewspaperHomepage()
    {
        // Get published blog posts
        $posts = \App\Modules\Blog\Models\Post::with(['author', 'categories', 'media'])
            ->where('status', 'published')
            ->latest('published_at')
            ->get();

        // Top stories from the TopStory model (managed via admin panel)
        $topStories = \App\Models\TopStory::with(['post.author', 'post.categories', 'post.media'])
            ->active()
            ->ordered()
            ->get()
            ->pluck('post')
            ->filter(); // Remove any null posts (if post was deleted)

        // Featured post is the first post from top stories
        $featuredPost = $topStories->first() ?? ($posts->where('is_featured', true)->first() ?? $posts->first());

        // Get featured categories (managed via admin panel)
        $featuredCategories = \App\Models\FeaturedCategory::with(['category.posts' => function ($q) {
            $q->where('status', 'published')
                ->with(['author', 'categories', 'media'])
                ->latest('published_at')
                ->limit(5); // Get 5 posts: 1 main + 4 side posts
        }])
            ->active()
            ->ordered()
            ->get()
            ->filter(function ($featured) {
                return $featured->category && $featured->category->posts->isNotEmpty();
            });

        // Build featured category sections
        $featuredCategorySections = [];
        foreach ($featuredCategories as $featured) {
            if ($featured->category) {
                // Get latest video post for this category
                $latestVideo = $featured->category->posts()
                    ->where('status', 'published')
                    ->whereNotNull('youtube_url')
                    ->where('youtube_url', '!=', '')
                    ->latest('published_at')
                    ->first();

                $featuredCategorySections[] = [
                    'category' => $featured->category,
                    'posts' => $featured->category->posts,
                    'latestVideo' => $latestVideo
                ];
            }
        }

        // Get section settings
        $featuredCategoriesEnabled = \App\Models\SiteSetting::get('featured_categories_section_enabled', '1');
        $featuredCategoriesTitle = \App\Models\SiteSetting::get('featured_categories_section_title', 'গুরুত্বপুর্ন বিভাগ');

        // Top Videos Section - Get all active top videos with their categories
        $topVideos = \App\Models\TopVideo::with(['post.author', 'post.categories', 'post.media'])
            ->active()
            ->ordered()
            ->get()
            ->pluck('post')
            ->filter(); // Remove any null posts

        // Group top videos by category
        $topVideosByCategory = [];
        foreach ($topVideos as $video) {
            foreach ($video->categories as $category) {
                if (!isset($topVideosByCategory[$category->id])) {
                    $topVideosByCategory[$category->id] = [
                        'category' => $category,
                        'videos' => collect()
                    ];
                }
                $topVideosByCategory[$category->id]['videos']->push($video);
            }
        }

        // Get section settings for top videos
        $topVideosEnabled = \App\Models\SiteSetting::get('top_videos_section_enabled', '1');
        $topVideosTitle = \App\Models\SiteSetting::get('top_videos_section_title', 'শীর্ষ ভিডিও');

        // Latest posts for sidebar
        $latestPosts = $posts->take(10);

        // Popular posts (most viewed)
        $popularPosts = \App\Modules\Blog\Models\Post::with(['author', 'categories'])
            ->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->get();

        // Get headline banner settings
        $headlineBanner = \App\Models\HeadlineBannerSettings::getSettings();

        // SEO data
        $siteName = SiteSetting::get('site_name', config('app.name'));
        $seoData = [
            'title' => $siteName . ' | ' . SiteSetting::get('site_tagline', 'Latest News & Articles'),
            'description' => SiteSetting::get('meta_description', 'Read the latest news, articles, and stories'),
            'keywords' => SiteSetting::get('meta_keywords', 'news, blog, articles, stories'),
            'og_image' => SiteSetting::get('site_logo') ? asset('storage/' . SiteSetting::get('site_logo')) : asset('images/og-default.jpg'),
            'og_type' => 'website',
            'canonical' => url('/'),
        ];

        return view('frontend.home.newspaper', compact(
            'featuredPost',
            'topStories',
            'featuredCategorySections',
            'featuredCategoriesEnabled',
            'featuredCategoriesTitle',
            'topVideosByCategory',
            'topVideosEnabled',
            'topVideosTitle',
            'latestPosts',
            'popularPosts',
            'headlineBanner',
            'seoData'
        ));
    }

    /**
     * Display shop page with all products
     *
     * @return \Illuminate\View\View
     */
    public function shop(Request $request)
    {
        $query = Product::with(['variants', 'categories', 'brand', 'images'])
            ->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $categoryIds = is_array($request->category) ? $request->category : [$request->category];
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        // Brand Filter
        if ($request->filled('brand')) {
            $brandIds = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereIn('brand_id', $brandIds);
        }

        // Price Range Filter
        if ($request->filled('min_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }
        if ($request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Rating Filter
        if ($request->filled('rating')) {
            $query->where('average_rating', '>=', $request->rating);
        }

        // In Stock Filter
        if ($request->filled('in_stock') && $request->in_stock == '1') {
            $query->whereHas('variants', function ($q) {
                $q->where('stock_quantity', '>', 0);
            });
        }

        // On Sale Filter
        if ($request->filled('on_sale') && $request->on_sale == '1') {
            $query->whereHas('variants', function ($q) {
                $q->whereNotNull('sale_price')
                    ->whereColumn('sale_price', '<', 'price');
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_id = products.id) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_id = products.id) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 24);
        $products = $query->paginate($perPage)->withQueryString();

        // Get all categories and brands for filters
        $categories = Category::where('is_active', true)
            ->withCount([
                'products' => function ($q) {
                    $q->where('is_active', true);
                }
            ])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('is_active', true)
            ->withCount([
                'products' => function ($q) {
                    $q->where('is_active', true);
                }
            ])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();

        // Get price range
        $priceRange = Product::where('is_active', true)
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->selectRaw('MIN(product_variants.price) as min_price, MAX(product_variants.price) as max_price')
            ->first();

        return view('frontend.shop.index', compact('products', 'categories', 'brands', 'priceRange'));
    }

    /**
     * Display about page
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('frontend.pages.about');
    }

    /**
     * Display contact page
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('frontend.pages.contact');
    }

    /**
     * Load a single category section for lazy loading
     *
     * @param int $categoryId
     * @return \Illuminate\Http\Response
     */
    public function loadCategorySection($categoryId)
    {
        // Find blog category with published posts
        $category = \App\Modules\Blog\Models\BlogCategory::with(['posts' => function ($query) {
            $query->where('status', 'published')
                ->with(['author', 'categories', 'media'])
                ->latest('published_at')
                ->limit(7); // 1 main + 2 side + 4 grid posts
        }])->findOrFail($categoryId);

        // Get latest video post for this category
        $latestVideo = \App\Modules\Blog\Models\Post::where('status', 'published')
            ->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('blog_categories.id', $categoryId);
            })
            ->whereNotNull('youtube_url')
            ->where('youtube_url', '!=', '')
            ->latest('published_at')
            ->first();

        $section = [
            'category' => $category,
            'posts' => $category->posts,
            'latestVideo' => $latestVideo
        ];

        // Return HTML partial
        return view('frontend.home.partials.category-section', compact('section'))->render();
    }

    /**
     * Load top videos section for lazy loading
     *
     * @return \Illuminate\Http\Response
     */
    public function loadTopVideosSection()
    {
        // Get all active top videos with their categories
        $topVideos = \App\Models\TopVideo::with(['post.author', 'post.categories', 'post.media'])
            ->active()
            ->ordered()
            ->get()
            ->pluck('post')
            ->filter(); // Remove any null posts

        // Group top videos by category
        $topVideosByCategory = [];
        foreach ($topVideos as $video) {
            foreach ($video->categories as $category) {
                if (!isset($topVideosByCategory[$category->id])) {
                    $topVideosByCategory[$category->id] = [
                        'category' => $category,
                        'videos' => collect()
                    ];
                }
                $topVideosByCategory[$category->id]['videos']->push($video);
            }
        }

        // Get section settings
        $topVideosEnabled = \App\Models\SiteSetting::get('top_videos_section_enabled', '1');
        $topVideosTitle = \App\Models\SiteSetting::get('top_videos_section_title', 'শীর্ষ ভিডিও');

        // Return HTML partial
        return view('frontend.home.partials.top-videos-content', compact(
            'topVideosByCategory',
            'topVideosEnabled',
            'topVideosTitle'
        ))->render();
    }
}
