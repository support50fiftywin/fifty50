@extends('layouts.public')

@section('content')
<style>
.hero {
    background: #000;
    color: #fff;
    padding: 60px 20px;
    text-align: center;
}
.prize-img {
    max-width: 420px;
    width: 100%;
    border-radius: 12px;
    margin: 25px auto;
}
.cta-btn {
    background: #FF4433;
    padding: 14px 34px;
    border-radius: 6px;
    font-size: 20px;
    font-weight: 600;
    color: #fff;
    display: inline-block;
    margin-top: 15px;
}
.cta-btn:hover {
    background: #d93d2f;
}
.share-section a {
    text-decoration: none;
    font-weight: 600;
    color: #FF4433;
}
.share-section a:hover {
    text-decoration: underline;
}
</style>

<div class="hero">
    <h1>{{ $merchant->business_name }} × 50Fifty WIN</h1>
    <p>Every purchase supports this business and earns sweepstakes entries! 🎉</p>
</div>

<div class="container mt-5 text-center">

    @if(isset($sweepstake))
        <h2 class="fw-bold">🔥 Current Prize: {{ $sweepstake->prize_title }}</h2>

        @if($sweepstake->prize_image)
            <img src="{{ asset('storage/prizes/' . $sweepstake->prize_image) }}" class="prize-img">
        @endif

       <a href="{{ route('checkout', [$merchant->id, $packages->id]) }}" class="btn btn-primary">
			Enter Sweepstakes
		</a>

    @else
        <h3 class="fw-bold mt-4">🎁 No active sweepstake right now</h3>
        <p>Please check back soon!</p>
    @endif
	
    @if(session('unclaimed_entries'))
    <a href="{{ route('claim.entries') }}" class="btn btn-success btn-lg">
        Claim Your Entries
    </a>
	@endif

</div>

<div class="text-center mt-5 share-section">
    <h4 class="mb-2">Share this page</h4>

    @php $shareUrl = urlencode(url()->current()); @endphp

    <a href="https://wa.me/?text={{ $shareUrl }}" target="_blank">WhatsApp</a> |
    <a href="https://facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank">Facebook</a> |
    <a href="https://www.instagram.com/?url={{ $shareUrl }}" target="_blank">Instagram</a>
</div>
@endsection
