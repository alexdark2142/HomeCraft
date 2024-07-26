<section
    class="section swiper-container swiper-slider swiper-slider-modern"
    data-loop="true"
    data-autoplay="5000"
    data-simulate-touch="true"
    data-nav="true"
    data-slide-effect="fade"
>
    <div class="swiper-wrapper text-left">
        @foreach($sliders as $slider)
            <div class="swiper-slide context-dark" data-slide-bg="{{ asset('images/sliders/' . $slider->image_name) }}">
                <div class="swiper-slide-caption">
                    <div class="container">
                        <div class="row justify-content-center justify-content-xxl-start">
                            <div class="col-md-10 col-xxl-6">
                                <div class="slider-modern-box">
                                    @if($slider->title)
                                        <h1 class="slider-modern-title">
                                            <span data-caption-animate="slideInDown" data-caption-delay="0">
                                               {{ $slider->title }}
                                            </span>
                                        </h1>
                                    @endif

                                    @if($slider->description)
                                        <p data-caption-animate="fadeInRight" data-caption-delay="400">
                                            {{ $slider->description }}
                                        </p>
                                    @endif

                                    <div class="oh button-wrap">
                                        <a
                                            class="button button-primary button-ujarak"
                                            href="/products/{{ $slider->category->filter_name }}"
                                            data-caption-animate="slideInLeft"
                                            data-caption-delay="400"
                                        >
                                            {{ $slider->category->name }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Swiper Navigation-->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    <!-- Swiper Pagination-->
    <div class="swiper-pagination swiper-pagination-style-2"></div>
</section>
