<section class="section section-md bg-default section-top-image">
    <div class="container">
        <div class="row row-30 justify-content-center">
            @foreach ($categories as $category)
                <div class="col-sm-6 col-lg-4 col-xl-3 category-main">
                    <a href="/products/{{ $category->filter_name }}" class="box-icon-ruby">
                        {{ $category->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
