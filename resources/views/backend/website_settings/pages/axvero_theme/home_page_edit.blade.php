@extends('backend.layouts.app')

@section('content')
	<div class="page-content">
		<div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem border-bottom border-gray">
			<div class="row align-items-center">
				<div class="col">
					<h1 class="h3">{{ translate('Homepage Settings (Axvero Theme)') }}</h1>
				</div>
			</div>
		</div>

		<div class="d-sm-flex">
			<!-- page side nav -->
			<div class="page-side-nav c-scrollbar-light px-3 py-2">
				<ul class="nav nav-tabs flex-sm-column border-0" role="tablist" aria-orientation="vertical">
					<!-- Hero Section -->
					<li class="nav-item">
						<a class="nav-link active" id="hero-tab" href="#hero_section"
							data-toggle="tab" data-target="#hero_section" type="button" role="tab" aria-controls="hero_section" aria-selected="true">
							{{ translate('Hero Section') }}
						</a>
					</li>
					<!-- Latest Edition -->
					<li class="nav-item">
						<a class="nav-link" id="latest-tab" href="#latest_edition"
							data-toggle="tab" data-target="#latest_edition" type="button" role="tab" aria-controls="latest_edition" aria-selected="false">
							{{ translate('Latest Edition Promo') }}
						</a>
					</li>
					<!-- Promo Banner -->
					<li class="nav-item">
						<a class="nav-link" id="promo-tab" href="#promo_banner"
							data-toggle="tab" data-target="#promo_banner" type="button" role="tab" aria-controls="promo_banner" aria-selected="false">
							{{ translate('Promo Banner (30% Off)') }}
						</a>
					</li>
					<!-- Special Offers -->
					<li class="nav-item">
						<a class="nav-link" id="offers-tab" href="#special_offers"
							data-toggle="tab" data-target="#special_offers" type="button" role="tab" aria-controls="special_offers" aria-selected="false">
							{{ translate('Special Offers Banners') }}
						</a>
					</li>
				</ul>
			</div>

			<!-- tab content -->
			<div class="flex-grow-1 p-sm-3 p-lg-2rem mb-2rem mb-md-0">
				<div class="tab-content">

					<!-- Language Bar -->
					<ul class="nav nav-tabs nav-fill language-bar mb-3">
						@foreach (get_all_active_language() as $key => $language)
							<li class="nav-item">
								<a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3"
									href="{{route('custom-pages.edit', ['id'=>$page->slug, 'lang'=>$language->code, 'page'=>'home'] )}}">
									<img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
										height="11" class="mr-1">
									<span>{{ $language->name }}</span>
								</a>
							</li>
						@endforeach
					</ul>

					<!-- Hero Section Form -->
					<div class="tab-pane fade show active" id="hero_section" role="tabpanel" aria-labelledby="hero-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_hero_title">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_hero_subtitle">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_hero_btn_text">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_hero_btn_link">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_hero_image">

							<div class="bg-white p-3 p-sm-2rem">
								<h6 class="mb-4">{{ translate('Hero Section Configuration') }}</h6>
								<div class="form-group">
									<label>{{ translate('Title') }}</label>
									<input type="text" class="form-control" name="axvero_hero_title" value="{{ get_setting('axvero_hero_title', null, $lang) }}" placeholder="e.g. New Fashion">
								</div>
								<div class="form-group">
									<label>{{ translate('Subtitle') }}</label>
									<textarea class="form-control" name="axvero_hero_subtitle" rows="3">{{ get_setting('axvero_hero_subtitle', null, $lang) }}</textarea>
								</div>
								<div class="row">
									<div class="col-md-6 form-group">
										<label>{{ translate('Button Text') }}</label>
										<input type="text" class="form-control" name="axvero_hero_btn_text" value="{{ get_setting('axvero_hero_btn_text', null, $lang) }}">
									</div>
									<div class="col-md-6 form-group">
										<label>{{ translate('Button Link') }}</label>
										<input type="text" class="form-control" name="axvero_hero_btn_link" value="{{ get_setting('axvero_hero_btn_link', null, $lang) }}">
									</div>
								</div>
								<div class="form-group">
									<label>{{ translate('Hero Model Image') }}</label>
									<div class="input-group" data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend">
											<div class="input-group-text bg-soft-secondary">{{ translate('Browse')}}</div>
										</div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="axvero_hero_image" class="selected-files" value="{{ get_setting('axvero_hero_image', null, $lang) }}">
									</div>
									<div class="file-preview box sm"></div>
								</div>
								<div class="text-right">
									<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Latest Edition Form -->
					<div class="tab-pane fade" id="latest_edition" role="tabpanel" aria-labelledby="latest-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_latest_title">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_latest_subtitle">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_latest_price">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_latest_link">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_latest_bg_image">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_latest_product_image">

							<div class="bg-white p-3 p-sm-2rem">
								<h6 class="mb-4">{{ translate('Latest Edition configuration') }}</h6>
								<div class="form-group">
									<label>{{ translate('Product Title') }}</label>
									<input type="text" class="form-control" name="axvero_latest_title" value="{{ get_setting('axvero_latest_title', null, $lang) }}" placeholder="e.g. Skyline Tee">
								</div>
								<div class="form-group">
									<label>{{ translate('Subtitle') }}</label>
									<input type="text" class="form-control" name="axvero_latest_subtitle" value="{{ get_setting('axvero_latest_subtitle', null, $lang) }}">
								</div>
								<div class="row">
									<div class="col-md-6 form-group">
										<label>{{ translate('Price') }}</label>
										<input type="text" class="form-control" name="axvero_latest_price" value="{{ get_setting('axvero_latest_price', null, $lang) }}">
									</div>
									<div class="col-md-6 form-group">
										<label>{{ translate('Button Link') }}</label>
										<input type="text" class="form-control" name="axvero_latest_link" value="{{ get_setting('axvero_latest_link', null, $lang) }}">
									</div>
								</div>
								
								<div class="form-group">
									<label>{{ translate('Background Image') }}</label>
									<div class="input-group" data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend"><div class="input-group-text bg-soft-secondary">{{ translate('Browse')}}</div></div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="axvero_latest_bg_image" class="selected-files" value="{{ get_setting('axvero_latest_bg_image', null, $lang) }}">
									</div>
									<div class="file-preview box sm"></div>
								</div>
								<div class="form-group">
									<label>{{ translate('Product Image') }}</label>
									<div class="input-group" data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend"><div class="input-group-text bg-soft-secondary">{{ translate('Browse')}}</div></div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="axvero_latest_product_image" class="selected-files" value="{{ get_setting('axvero_latest_product_image', null, $lang) }}">
									</div>
									<div class="file-preview box sm"></div>
								</div>

								<div class="text-right">
									<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Promo Banner Form -->
					<div class="tab-pane fade" id="promo_banner" role="tabpanel" aria-labelledby="promo-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_promo_title">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_promo_subtitle">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_promo_link">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_promo_image_1">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_promo_image_2">

							<div class="bg-white p-3 p-sm-2rem">
								<h6 class="mb-4">{{ translate('Promo Banner (30% Off)') }}</h6>
								<div class="row">
									<div class="col-md-6 form-group">
										<label>{{ translate('Title') }}</label>
										<input type="text" class="form-control" name="axvero_promo_title" value="{{ get_setting('axvero_promo_title', null, $lang) }}">
									</div>
									<div class="col-md-6 form-group">
										<label>{{ translate('Subtitle') }}</label>
										<input type="text" class="form-control" name="axvero_promo_subtitle" value="{{ get_setting('axvero_promo_subtitle', null, $lang) }}">
									</div>
								</div>
								<div class="form-group">
									<label>{{ translate('Button Link') }}</label>
									<input type="text" class="form-control" name="axvero_promo_link" value="{{ get_setting('axvero_promo_link', null, $lang) }}">
								</div>
								
								<div class="row">
									<div class="col-md-6 form-group">
										<label>{{ translate('Image 1') }}</label>
										<div class="input-group" data-toggle="aizuploader" data-type="image">
											<div class="input-group-prepend"><div class="input-group-text bg-soft-secondary">{{ translate('Browse')}}</div></div>
											<div class="form-control file-amount">{{ translate('Choose File') }}</div>
											<input type="hidden" name="axvero_promo_image_1" class="selected-files" value="{{ get_setting('axvero_promo_image_1', null, $lang) }}">
										</div>
										<div class="file-preview box sm"></div>
									</div>
									<div class="col-md-6 form-group">
										<label>{{ translate('Image 2') }}</label>
										<div class="input-group" data-toggle="aizuploader" data-type="image">
											<div class="input-group-prepend"><div class="input-group-text bg-soft-secondary">{{ translate('Browse')}}</div></div>
											<div class="form-control file-amount">{{ translate('Choose File') }}</div>
											<input type="hidden" name="axvero_promo_image_2" class="selected-files" value="{{ get_setting('axvero_promo_image_2', null, $lang) }}">
										</div>
										<div class="file-preview box sm"></div>
									</div>
								</div>

								<div class="text-right">
									<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Special Offers Banners -->
					<div class="tab-pane fade" id="special_offers" role="tabpanel" aria-labelledby="offers-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_offer_1_title">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_offer_1_subtitle">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_offer_1_link">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_offer_1_bg">
							
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_offer_2_title">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_offer_2_subtitle">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_offer_2_link">
							<input type="hidden" name="types[][{{ $lang }}]" value="axvero_offer_2_bg">

							<div class="bg-white p-3 p-sm-2rem mb-4">
								<h6 class="mb-4">{{ translate('Banner 1 (Left)') }}</h6>
								<div class="row">
									<div class="col-md-6 form-group">
										<label>{{ translate('Title') }}</label>
										<input type="text" class="form-control" name="axvero_offer_1_title" value="{{ get_setting('axvero_offer_1_title', null, $lang) }}">
									</div>
									<div class="col-md-6 form-group">
										<label>{{ translate('Subtitle') }}</label>
										<input type="text" class="form-control" name="axvero_offer_1_subtitle" value="{{ get_setting('axvero_offer_1_subtitle', null, $lang) }}">
									</div>
								</div>
								<div class="form-group">
									<label>{{ translate('Link') }}</label>
									<input type="text" class="form-control" name="axvero_offer_1_link" value="{{ get_setting('axvero_offer_1_link', null, $lang) }}">
								</div>
								<div class="form-group">
									<label>{{ translate('Background Image') }}</label>
									<div class="input-group" data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend"><div class="input-group-text bg-soft-secondary">{{ translate('Browse')}}</div></div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="axvero_offer_1_bg" class="selected-files" value="{{ get_setting('axvero_offer_1_bg', null, $lang) }}">
									</div>
									<div class="file-preview box sm"></div>
								</div>
							</div>

							<div class="bg-white p-3 p-sm-2rem">
								<h6 class="mb-4">{{ translate('Banner 2 (Right)') }}</h6>
								<div class="row">
									<div class="col-md-6 form-group">
										<label>{{ translate('Title') }}</label>
										<input type="text" class="form-control" name="axvero_offer_2_title" value="{{ get_setting('axvero_offer_2_title', null, $lang) }}">
									</div>
									<div class="col-md-6 form-group">
										<label>{{ translate('Subtitle') }}</label>
										<input type="text" class="form-control" name="axvero_offer_2_subtitle" value="{{ get_setting('axvero_offer_2_subtitle', null, $lang) }}">
									</div>
								</div>
								<div class="form-group">
									<label>{{ translate('Link') }}</label>
									<input type="text" class="form-control" name="axvero_offer_2_link" value="{{ get_setting('axvero_offer_2_link', null, $lang) }}">
								</div>
								<div class="form-group">
									<label>{{ translate('Background Image') }}</label>
									<div class="input-group" data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend"><div class="input-group-text bg-soft-secondary">{{ translate('Browse')}}</div></div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="axvero_offer_2_bg" class="selected-files" value="{{ get_setting('axvero_offer_2_bg', null, $lang) }}">
									</div>
									<div class="file-preview box sm"></div>
								</div>

								<div class="text-right">
									<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
								</div>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
@endsection
