<?php

?>

@extends('layout')

@section('title', 'Мониторинг')

@section('head')
	<script
			src="https://code.jquery.com/jquery-3.5.1.min.js"
			integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
			crossorigin="anonymous">
	</script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
		  integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
			crossorigin="anonymous"></script></script>

    <script src="/public/js/service/candle.js"></script>

	<script src="/public/js/service/profiles.js"></script>

	<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')

<div class="search-outer">
	<div class="bookmarks d-flex flex-row">
		<div class="chosen-pairs_btn bookmark-item">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="css-96g3sl">
				<path fill-rule="evenodd" clip-rule="evenodd"
					  d="M11.06 1.579l1.811-.008 2.736 5.765 6.046.937.563 1.686-4.415 4.526 1.015 6.357-1.473 1.032-5.375-2.987-5.375 2.987-1.472-1.04 1.074-6.353-4.41-4.522.562-1.686 6.04-.936 2.674-5.758z"
					  fill="currentColor">
				</path>
			</svg>
		</div>
		<div class="bookmark-item">
			BNB
		</div>
		<div class="bookmark-item">
			BTC
		</div>
		<div class="bookmark-item">
			ALTS
		</div>
		<div class="bookmark-item">
			FIAT
		</div>
	</div>
	<div class="control-panel"></div>
	<div class="pair-list"></div>
</div>

@endsection

{{--<div name="market" class="css-m3ytwy">--}}
{{--	<div class="css-mxayi7">--}}
{{--		<div style="padding: 0px 16px; overflow: hidden;">--}}
{{--			<div class="css-1oe4hjg">--}}
{{--				<div style="margin-right: 2px;" class="css-8huhn8"><span><svg xmlns="http://www.w3.org/2000/svg"--}}
{{--																			  viewBox="0 0 24 24" fill="none"--}}
{{--																			  class="css-96g3sl"><path--}}
{{--									fill-rule="evenodd" clip-rule="evenodd"--}}
{{--									d="M11.06 1.579l1.811-.008 2.736 5.765 6.046.937.563 1.686-4.415 4.526 1.015 6.357-1.473 1.032-5.375-2.987-5.375 2.987-1.472-1.04 1.074-6.353-4.41-4.522.562-1.686 6.04-.936 2.674-5.758z"--}}
{{--									fill="currentColor"></path></svg></span></div>--}}
{{--				<div style="margin-right: 2px;" class="css-8huhn8"><span>margin</span></div>--}}
{{--				<div style="margin-right: 2px;" class="css-t3r4c1"><span>BNB</span></div>--}}
{{--				<div style="margin-right: 2px;" class="css-8huhn8"><span>BTC</span></div>--}}
{{--				<div class="css-ybbx55" style="margin-right: 2px;">--}}
{{--					<div data-testid="BNBType" class="css-8huhn8">ALTS--}}
{{--						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="css-uwnjeb">--}}
{{--							<path d="M16 9v1.2L12 15l-4-4.8V9h8z"></path>--}}
{{--						</svg>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--				<div class="css-ybbx55" style="margin-right: 2px;">--}}
{{--					<div data-testid="BNBType" class="css-8huhn8">FIAT--}}
{{--						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="css-uwnjeb">--}}
{{--							<path d="M16 9v1.2L12 15l-4-4.8V9h8z"></path>--}}
{{--						</svg>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--				<div class="css-ybbx55" style="margin-right: 2px;">--}}
{{--					<div data-testid="BNBType" class="css-8huhn8">Зоны--}}
{{--						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="css-uwnjeb">--}}
{{--							<path d="M16 9v1.2L12 15l-4-4.8V9h8z"></path>--}}
{{--						</svg>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--			</div>--}}
{{--			<div style="display: flex; flex-wrap: wrap;">--}}
{{--				<div class=" css-1ugx9ln">--}}
{{--					<div class="bn-input-prefix css-vurnku">--}}
{{--						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="css-1tw16a6">--}}
{{--							<path d="M3 10.982c0 3.845 3.137 6.982 6.982 6.982 1.518 0 3.036-.506 4.149-1.416L18.583 21 20 19.583l-4.452-4.452c.81-1.113 1.416-2.631 1.416-4.149 0-1.922-.81-3.643-2.023-4.958C13.726 4.81 11.905 4 9.982 4 6.137 4 3 7.137 3 10.982zM13.423 7.44a4.819 4.819 0 011.416 3.441c0 1.315-.506 2.53-1.416 3.44a4.819 4.819 0 01-3.44 1.417 4.819 4.819 0 01-3.441-1.417c-1.012-.81-1.518-2.023-1.518-3.339 0-1.315.506-2.53 1.416-3.44.911-1.012 2.227-1.518 3.542-1.518 1.316 0 2.53.506 3.44 1.416z"--}}
{{--								  fill="currentColor"></path>--}}
{{--						</svg>--}}
{{--					</div>--}}
{{--					<input data-bn-type="input" placeholder="Поиск" class="test css-1fnphnh" data-testid="searchInput"--}}
{{--						   value=""></div>--}}
{{--				<div class="radio-group-wrap css-4cffwv"--}}
{{--					 style="display: flex; justify-content: center; align-items: center; font-size: 0px;">--}}
{{--					<div data-testid="changeSelect" class="css-sk5xsj"><label class="css-f5h6sg">--}}
{{--							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none" class="css-pojy5h">--}}
{{--								<circle cx="8" cy="8" r="7.5" stroke="currentColor"></circle>--}}
{{--								<circle cx="8" cy="8" r="4" fill="currentColor"></circle>--}}
{{--							</svg>--}}
{{--							<div class="css-lzd0h4">Изменение<input--}}
{{--										style="clip: rect(0px, 0px, 0px, 0px); position: absolute;" type="radio"--}}
{{--										data-bn-type="radio" name="radio" value="change" checked="" hidden=""></div>--}}
{{--						</label></div>--}}
{{--					<div data-testid="volumeSeletct" class="css-meu6ns"><label class="css-f5h6sg">--}}
{{--							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none" class="css-pojy5h">--}}
{{--								<circle cx="8" cy="8" r="7.5" stroke="currentColor"></circle>--}}
{{--							</svg>--}}
{{--							<div class="css-lzd0h4">Объем<input--}}
{{--										style="clip: rect(0px, 0px, 0px, 0px); position: absolute;" type="radio"--}}
{{--										data-bn-type="radio" name="radio" value="volume" hidden=""></div>--}}
{{--						</label></div>--}}
{{--				</div>--}}
{{--			</div>--}}
{{--			<div class="css-brgm30">--}}
{{--				<div class="content">--}}
{{--					<div class="item" style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--						<div data-bn-type="text" title="Пара" class="css-zssnwj">Пара</div>--}}
{{--						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="css-tnfu15">--}}
{{--							<path opacity="0.5" d="M16 12.85v1.65L12.75 18 9.5 14.5v-1.65H16z" fill="#848E9C"></path>--}}
{{--							<path d="M9.5 9.745v-1.65l3.25-3.5 3.25 3.5v1.65H9.5z"--}}
{{--								  fill="url(#sorting-up-color-s24_svg__paint0_linear)"></path>--}}
{{--							<defs>--}}
{{--								<linearGradient id="sorting-up-color-s24_svg__paint0_linear" x1="16" y1="4.594" x2="9.5"--}}
{{--												y2="4.594" gradientUnits="userSpaceOnUse">--}}
{{--									<stop stop-color="#EFB80B"></stop>--}}
{{--									<stop offset="1" stop-color="#FBDA3C"></stop>--}}
{{--								</linearGradient>--}}
{{--							</defs>--}}
{{--						</svg>--}}
{{--					</div>--}}
{{--					<div class="item" style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--						<div data-bn-type="text" title="Цена" class="css-zssnwj">Цена</div>--}}
{{--						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="css-tnfu15">--}}
{{--							<path d="M9 10.368v-1.4L11.968 6l2.968 2.968v1.4H9zM14.936 13v1.4l-2.968 2.968L9 14.4V13h5.936z"--}}
{{--								  fill="#C1C6CD"></path>--}}
{{--						</svg>--}}
{{--					</div>--}}
{{--					<div class="item" style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--						<div data-bn-type="text" title="Изменение" class="css-zssnwj">Изменение</div>--}}
{{--						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="css-tnfu15">--}}
{{--							<path d="M9 10.368v-1.4L11.968 6l2.968 2.968v1.4H9zM14.936 13v1.4l-2.968 2.968L9 14.4V13h5.936z"--}}
{{--								  fill="#C1C6CD"></path>--}}
{{--						</svg>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--			</div>--}}
{{--		</div>--}}
{{--		<div class="list-container css-8vnk1u">--}}
{{--			<div class="list-auto-sizer" style="overflow: visible; height: 0px; width: 0px;">--}}
{{--				<div class="fixed-size-list"--}}
{{--					 style="position: relative; height: 299px; width: 320px; overflow: auto; will-change: transform; direction: ltr;">--}}
{{--					<div style="height: 2952px; width: 100%;">--}}
{{--						<div style="position: absolute; left: 0px; top: 0px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/AAVE_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">AAVE</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.7801</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-1.58%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 24px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/ADA_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">ADA</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.004171</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-0.50%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 48px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/ALGO_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">ALGO</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-buy">0.002830</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-3.84%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 72px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/ALPHA_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">ALPHA</span>/BNB--}}
{{--										</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-buy">0.0016296</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-1.71%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 96px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/ANKR_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">ANKR</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-buy">0.0002166</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-1.50%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 120px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/ANT_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">ANT</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.01223</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-2.39%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 144px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/AR_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">AR</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.04916</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-buy">+4.48%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 168px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/ARPA_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">ARPA</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.0001150</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-1.54%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 192px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/ATA_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">ATA</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-buy">0.0021986</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-buy">+2.44%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 216px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/ATOM_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">ATOM</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.03630</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-2.21%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 240px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/AVA_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">AVA</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.00826</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-1.20%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 264px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/AVAX_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">AVAX</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.04058</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-1.84%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 288px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/AXS_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">AXS</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.012735</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-4.13%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--						<div style="position: absolute; left: 0px; top: 312px; height: 24px; width: 100%;"--}}
{{--							 class="list-item-container">--}}
{{--							<div class="tradeMarketColumnWrap css-ybbx55"><a class="content" href="/ru/trade/BAKE_BNB">--}}
{{--									<div class="item item-symbol"--}}
{{--										 style="flex: 90 1 0px; justify-content: flex-start; min-width: 120px;">--}}
{{--										<div class="favorite ">--}}
{{--											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"--}}
{{--												 class="css-1wngn4j">--}}
{{--												<path d="M21.4 10.8c-.3-1.1-.3-1.1-.7-2.1l-6-.1L12.8 3h-2.2l-2 5.6-5.9.1c-.3 1.1-.3 1.1-.7 2.1l4.8 3.6L5 20.1l1.8 1.3 4.9-3.4 4.9 3.4c.9-.7.9-.6 1.8-1.3l-1.8-5.7 4.8-3.6z"--}}
{{--													  fill="currentColor"></path>--}}
{{--											</svg>--}}
{{--										</div>--}}
{{--										<div class="item-symbol-text"><span class="item-symbol-ba">BAKE</span>/BNB</div>--}}
{{--									</div>--}}
{{--									<div class="item item-price"--}}
{{--										 style="flex: 80 1 0px; min-width: 60px; justify-content: flex-start;">--}}
{{--										<div class="item-price-text item-color-sell">0.008529</div>--}}
{{--									</div>--}}
{{--									<div class="item item-change"--}}
{{--										 style="flex: 65 1 0px; min-width: 65px; justify-content: flex-end;">--}}
{{--										<div class="item-change-text item-color-sell">-0.51%</div>--}}
{{--									</div>--}}
{{--								</a></div>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--			</div>--}}
{{--			<div class="resize-triggers">--}}
{{--				<div class="expand-trigger">--}}
{{--					<div style="width: 321px; height: 300px;"></div>--}}
{{--				</div>--}}
{{--				<div class="contract-trigger"></div>--}}
{{--			</div>--}}
{{--		</div>--}}
{{--	</div>--}}
{{--</div>--}}