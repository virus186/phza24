<div class="review_star_icon">
    @if($rating == 0)
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      @elseif($rating < 1 && $rating > 0)
      <i class="fas fa-star-half-alt"></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      @elseif($rating <= 1 && $rating > 0)
      <i class="fas fa-star"></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      @elseif($rating < 2 && $rating > 1)
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      @elseif($rating <= 2 && $rating > 1)
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      @elseif($rating < 3 && $rating > 2)
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      @elseif($rating <= 3 && $rating > 2)
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star "></i>
      <i class="fas fa-star non_rated "></i>
      <i class="fas fa-star non_rated "></i>
      @elseif($rating < 4 && $rating > 3)
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star "></i>
      <i class="fas fa-star-half-alt"></i>
      <i class="fas fa-star non_rated "></i>
      @elseif($rating <= 4 && $rating > 3)
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star "></i>
      <i class="fas fa-star "></i>
      <i class="fas fa-star non_rated "></i>
      @elseif($rating < 5 && $rating > 4)
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star "></i>
      <i class="fas fa-star "></i>
      <i class="fas fa-star-half-alt"></i>
      @else
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star "></i>
      <i class="fas fa-star "></i>
      <i class="fas fa-star "></i>
      @endif
  </div>