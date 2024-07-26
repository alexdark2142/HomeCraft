<section class="section section-md bg-default section-top-image">
    <div class="container">
        <div class="row justify-content-between">
            @foreach ($categories as $category)
                <div class="col-sm-6 col-lg-4 category-main">
                    <a href="/products/{{ $category->filter_name }}" class="box-icon-ruby">
                        <div class="category-image" style="background-image: url('/images/categories/{{ $category->image_name }}');">
                            <span class="category-name">{{ $category->name }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
