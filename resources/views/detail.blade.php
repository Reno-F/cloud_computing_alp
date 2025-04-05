<x-template>
    <div class="mb-3">
        <a href="{{ route('catalog') }}" class="btn btn-secondary">Back</a>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <section>
                <div id="carouselImage" class="carousel slide">
                    <div class="carousel-inner">
                        @foreach($product->images as $idx => $image)
                        <div class="carousel-item {{ $idx == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/product/'.$image->name) }}" class="d-block" style="max-height:500px">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselImage" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselImage" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </section>
        </div>
        <div class="col-lg-7">
            <div class="mt-3">
                <section>
                    <h3>{{ $product->name }}</h3>
                    <h1 class="fw-bold text-danger">Rp {{ number_format($product->price, 0, ',', '.') }}</h1>
                </section>

                <form class="my-4" method="post" action="{{ route('cart-add', ['id' => $product->id]) }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        Add to cart
                    </button>
                </form>

                <section>
                    <div class="fw-semibold mb-2">Description</div>
                    <p>{{ $product->description }}</p>
                </section>

                <!-- Review Section -->
                <section>
                    <h4>Reviews</h4>
                    @foreach($product->reviews as $review)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    <span class="text-muted">{{ $review->created_at->format('d M Y') }}</span>
                                </div>
                                <div>
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="fa {{ $i < $review->rating ? 'fa-star' : 'fa-star-o' }}" aria-hidden="true"></i>
                                    @endfor
                                </div>
                            </div>
                            <p>{{ $review->comment }}</p>
                        </div>
                    @endforeach
                </section>

                @auth
                <section class="mt-4">
                    <h4>Add a Review</h4>
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-control" id="rating" name="rating" required>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Good</option>
                                <option value="3">3 - Average</option>
                                <option value="2">2 - Poor</option>
                                <option value="1">1 - Terrible</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </section>
                @endauth
            </div>
        </div>
    </div>

    @can('edit_product', $product)
    <div class="position-fixed end-0 bottom-0 pe-3 pb-3">
        <a href="{{ route('product-edit', ['id' => $product->id]) }}" class="btn btn-success">
            <i class="fa fa-edit"></i>
            Edit product
        </a>
    </div>
    @endcan
</x-template>
