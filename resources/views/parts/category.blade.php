    <section class="section section-md bg-default section-top-image">
        <div class="container">
            <div class="row row-30 justify-content-center">
                @foreach ($categories as $category)
                    <div class="col-sm-6 col-lg-3 wow fadeInRight" data-wow-delay="0s">
                        <article class="box-icon-ruby {{ $category->filter_name == $activeCategory ? 'active' : '' }}">
                            <div
                                class="unit
                                box-icon-ruby-body
                                flex-column
                                flex-md-row
                                text-md-left
                                flex-lg-column
                                align-items-center
                                text-lg-center
                                flex-xl-row
                                text-xl-left"
                            >
                                <div class="unit-body">
                                    <h4 class="box-icon-ruby-title">
                                        @if($category->filter_name == $activeCategory)
                                            <a href="/">{{ $category->name }}</a>
                                        @else
                                            <a href="/products/{{ $category->filter_name }}">{{ $category->name }}</a>
                                        @endif
                                    </h4>
{{--                                    @if($category->subcategories)--}}
{{--                                        <ul class="subcategories">--}}
{{--                                            @foreach ($category->subcategories as $subcategory)--}}
{{--                                                <li class="subcategory">--}}
{{--                                                    <a href="/{{ $category->name . '/' . $subcategory->name }}">{{ $subcategory->name }}</a>--}}
{{--                                                </li>--}}
{{--                                            @endforeach--}}
{{--                                        </ul>--}}
{{--                                    @endif--}}
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
