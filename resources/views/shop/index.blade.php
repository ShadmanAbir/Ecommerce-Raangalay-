@extends('layouts.app')

@section('title', 'Home - Raangalay')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
<style>
    /* ============================================================
       RAANGALAY — THE BRANCH LINE
       The camera rides the body of the banyan tree like a train,
       branch to branch. Every stop arrives at a real photograph
       of a garment hanging in the tree. Journey ends at the ground.
       ============================================================ */

    :root {
        --gold: #c9a24b;
        --gold-soft: rgba(201, 162, 75, 0.75);
        --ink: #0d1208;
    }

    html { scroll-behavior: auto; }
    body { background: var(--ink); }

    .serif { font-family: 'Cormorant Garamond', serif; }

    /* ---------- Journey scroll track ---------- */
    .journey { position: relative; }

    /* ---------- The Camera (sticky stage) ---------- */
    .stage {
        position: sticky;
        top: 0;
        height: 100vh;
        width: 100%;
        overflow: hidden;
        background: var(--ink);
    }

    /* ---------- Scenes (stations on the branch line) ---------- */
    .scene {
        position: absolute;
        inset: 0;
        opacity: 0;
        will-change: transform, opacity, filter;
        pointer-events: none;
    }
    .scene.is-active { pointer-events: auto; }

    .scene-bg {
        position: absolute;
        inset: -8%;
        background-size: cover;
        background-position: center 32%;
        will-change: transform, filter;
    }
    .scene-bg.mirror { transform: scaleX(-1); }

    .scene-tint { position: absolute; inset: 0; }
    .tint-intro  { background: radial-gradient(ellipse at 50% 35%, rgba(0,0,0,0) 0%, rgba(6,10,4,0.55) 90%); }
    .tint-branch { background: linear-gradient(90deg, rgba(6,10,4,0.68) 0%, rgba(6,10,4,0.18) 48%, rgba(6,10,4,0.25) 100%); }
    .tint-branch.flip { background: linear-gradient(270deg, rgba(6,10,4,0.68) 0%, rgba(6,10,4,0.18) 48%, rgba(6,10,4,0.25) 100%); }
    .tint-trunk  { background: radial-gradient(ellipse at center, rgba(0,0,0,0.05) 0%, rgba(6,10,4,0.7) 100%); }
    .tint-ground { background: linear-gradient(180deg, rgba(6,10,4,0.25) 0%, rgba(6,10,4,0.75) 100%); }

    /* ============================================================
       TRAVEL LAYER — foliage rushing past between stations.
       Three parallax strips sweep across the frame, selling the
       feeling of riding along the branch line of the same tree.
       ============================================================ */
    .travel-layer {
        position: absolute;
        inset: 0;
        z-index: 20;
        pointer-events: none;
        opacity: 0;
        will-change: opacity;
    }
    .travel-strip {
        position: absolute;
        top: -10%;
        height: 120%;
        width: 160%;
        background-size: cover;
        background-position: center;
        will-change: transform, filter;
        filter: blur(10px) brightness(0.72) saturate(1.1);
    }
    .travel-strip.s1 { z-index: 3; opacity: 0.95; }
    .travel-strip.s2 { z-index: 2; opacity: 0.75; filter: blur(16px) brightness(0.6); }
    .travel-strip.s3 { z-index: 1; opacity: 0.55; filter: blur(24px) brightness(0.5); }

    /* horizontal motion-blur streaks over the travel layer */
    .travel-streaks {
        position: absolute;
        inset: 0;
        z-index: 4;
        opacity: 0.5;
        background: repeating-linear-gradient(90deg,
            rgba(255,255,255,0)   0px,
            rgba(233,240,220,0.06) 2px,
            rgba(255,255,255,0)   9px,
            rgba(255,255,255,0)  70px);
        mask-image: linear-gradient(180deg, transparent 0%, black 30%, black 70%, transparent 100%);
        -webkit-mask-image: linear-gradient(180deg, transparent 0%, black 30%, black 70%, transparent 100%);
    }

    /* ---------- Grain + cinematic frame ---------- */
    .film-grain {
        position: absolute;
        inset: 0;
        z-index: 28;
        pointer-events: none;
        opacity: 0.05;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='120' height='120' filter='url(%23n)' opacity='0.6'/%3E%3C/svg%3E");
    }
    .stage-vignette {
        position: absolute;
        inset: 0;
        z-index: 27;
        pointer-events: none;
        box-shadow: inset 0 0 180px rgba(0,0,0,0.55);
    }

    /* ---------- Scene content ---------- */
    .scene-content {
        position: absolute;
        inset: 0;
        z-index: 10;
        display: flex;
        align-items: center;
        will-change: transform, opacity;
    }

    /* Intro */
    .intro-inner { width: 100%; text-align: center; padding: 0 1.5rem; }
    .intro-eyebrow {
        color: var(--gold-soft);
        letter-spacing: 0.55em;
        font-size: clamp(0.65rem, 1.4vw, 0.85rem);
        text-transform: uppercase;
    }
    .intro-title {
        font-family: 'Cormorant Garamond', serif;
        color: #f7f3e8;
        font-size: clamp(3rem, 9vw, 8rem);
        font-weight: 500;
        line-height: 1.05;
        text-shadow: 0 6px 50px rgba(0,0,0,0.65);
    }
    .intro-sub {
        color: rgba(247,243,232,0.78);
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        font-size: clamp(1.1rem, 2.4vw, 1.7rem);
    }
    .gold-rule {
        width: 70px; height: 1px;
        background: linear-gradient(90deg, transparent, var(--gold), transparent);
        margin: 1.4rem auto;
    }

    /* Branch category card */
    .branch-card {
        max-width: 440px;
        padding: 2.6rem 2.5rem;
        background: linear-gradient(160deg, rgba(10,14,8,0.58), rgba(10,14,8,0.30));
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(201,162,75,0.28);
        border-radius: 2px;
        position: relative;
    }
    .branch-card::before,
    .branch-card::after {
        content: '';
        position: absolute;
        width: 26px; height: 26px;
        border-color: var(--gold);
        border-style: solid;
        opacity: 0.85;
    }
    .branch-card::before { top: -1px; left: -1px; border-width: 1px 0 0 1px; }
    .branch-card::after  { bottom: -1px; right: -1px; border-width: 0 1px 1px 0; }

    .branch-index {
        color: var(--gold-soft);
        font-size: 0.72rem;
        letter-spacing: 0.5em;
        text-transform: uppercase;
    }
    .branch-name {
        font-family: 'Cormorant Garamond', serif;
        color: #f7f3e8;
        font-size: clamp(2.4rem, 5vw, 3.6rem);
        font-weight: 500;
        line-height: 1.08;
        margin: 0.9rem 0 0.4rem;
    }
    .branch-verse {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        color: rgba(247,243,232,0.68);
        font-size: clamp(1rem, 1.8vw, 1.15rem);
        line-height: 1.55;
    }
    .branch-rule {
        width: 54px; height: 1px;
        background: linear-gradient(90deg, var(--gold), transparent);
        margin: 1.3rem 0;
    }
    .branch-link {
        display: inline-flex;
        align-items: center;
        gap: 0.9rem;
        color: #f7f3e8;
        font-size: 0.78rem;
        letter-spacing: 0.35em;
        text-transform: uppercase;
        text-decoration: none;
        padding-bottom: 0.35rem;
        border-bottom: 1px solid rgba(201,162,75,0.45);
        transition: border-color 0.4s ease, color 0.4s ease, gap 0.4s ease;
    }
    .branch-link:hover { color: var(--gold); border-color: var(--gold); gap: 1.3rem; }
    .branch-link svg { width: 18px; height: 10px; }

    .content-left  { justify-content: flex-start; padding-left: clamp(1.5rem, 7vw, 8rem); }
    .content-right { justify-content: flex-end;  padding-right: clamp(1.5rem, 7vw, 8rem); }

    /* Trunk interlude */
    .trunk-inner { width: 100%; text-align: center; padding: 0 1.5rem; }
    .trunk-verse {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        color: #f7f3e8;
        font-size: clamp(1.5rem, 3.6vw, 2.7rem);
        line-height: 1.5;
        max-width: 820px;
        margin: 0 auto;
        text-shadow: 0 4px 40px rgba(0,0,0,0.7);
    }
    .trunk-sig {
        color: var(--gold-soft);
        letter-spacing: 0.5em;
        font-size: 0.72rem;
        text-transform: uppercase;
        margin-top: 2rem;
    }

    /* Ground / ending */
    .ground-inner { width: 100%; text-align: center; padding: 0 1.5rem; }
    .ground-title {
        font-family: 'Cormorant Garamond', serif;
        color: #f7f3e8;
        font-size: clamp(2.4rem, 6vw, 4.6rem);
        font-weight: 500;
        line-height: 1.15;
        text-shadow: 0 6px 50px rgba(0,0,0,0.7);
    }
    .ground-sub {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        color: rgba(247,243,232,0.72);
        font-size: clamp(1.05rem, 2.2vw, 1.45rem);
        margin-top: 0.8rem;
    }
    .ground-cta {
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        margin-top: 2.4rem;
        padding: 1.05rem 3.2rem;
        border: 1px solid var(--gold);
        color: #f7f3e8;
        font-size: 0.78rem;
        letter-spacing: 0.4em;
        text-transform: uppercase;
        text-decoration: none;
        background: rgba(201,162,75,0.08);
        transition: background 0.5s ease, color 0.5s ease;
    }
    .ground-cta:hover { background: var(--gold); color: var(--ink); }

    /* ---------- Progress rail ---------- */
    .rail {
        position: absolute;
        right: clamp(0.9rem, 2.5vw, 2.2rem);
        top: 50%;
        transform: translateY(-50%);
        z-index: 40;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .rail-dot {
        width: 34px; height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: transparent;
        border: none;
        padding: 0;
        position: relative;
    }
    .rail-dot::before {
        content: '';
        width: 5px; height: 5px;
        border-radius: 50%;
        background: rgba(247,243,232,0.35);
        transition: all 0.45s ease;
    }
    .rail-dot.active::before {
        background: var(--gold);
        box-shadow: 0 0 12px rgba(201,162,75,0.9);
        transform: scale(1.7);
    }
    .rail-dot .rail-label {
        position: absolute;
        right: 42px;
        top: 50%;
        transform: translateY(-50%) translateX(8px);
        white-space: nowrap;
        color: rgba(247,243,232,0.0);
        font-size: 0.62rem;
        letter-spacing: 0.32em;
        text-transform: uppercase;
        transition: all 0.4s ease;
        pointer-events: none;
    }
    .rail-dot:hover .rail-label,
    .rail-dot.active .rail-label {
        color: rgba(247,243,232,0.85);
        transform: translateY(-50%) translateX(0);
    }
    .rail-line { width: 1px; height: 22px; background: rgba(247,243,232,0.18); }

    /* ---------- Scroll hint ---------- */
    .scroll-hint {
        position: absolute;
        bottom: 34px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 40;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        transition: opacity 0.6s ease;
    }
    .scroll-hint span {
        color: rgba(247,243,232,0.6);
        font-size: 0.62rem;
        letter-spacing: 0.45em;
        text-transform: uppercase;
    }
    .scroll-hint .thread {
        width: 1px; height: 46px;
        background: linear-gradient(to bottom, var(--gold-soft), transparent);
        position: relative;
        overflow: hidden;
    }
    .scroll-hint .thread::after {
        content: '';
        position: absolute;
        top: -14px; left: 0;
        width: 1px; height: 14px;
        background: #f7f3e8;
        animation: threadDrop 1.8s ease-in-out infinite;
    }
    @keyframes threadDrop {
        0%   { top: -14px; opacity: 0; }
        30%  { opacity: 1; }
        100% { top: 46px; opacity: 0; }
    }

    /* ---------- Floating dust motes ---------- */
    .mote {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(247,240,215,0.9), rgba(247,240,215,0));
        pointer-events: none;
        z-index: 25;
        animation: moteDrift linear infinite;
        opacity: 0;
    }
    @keyframes moteDrift {
        0%   { transform: translate3d(0, 0, 0); opacity: 0; }
        12%  { opacity: 0.55; }
        88%  { opacity: 0.55; }
        100% { transform: translate3d(40px, -90vh, 0); opacity: 0; }
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 1024px) {
        .rail .rail-label { display: none; }
    }
    @media (max-width: 768px) {
        .scene-content.content-left,
        .scene-content.content-right {
            justify-content: center;
            padding: 0 1.25rem;
            align-items: flex-end;
        }
        .branch-card { margin-bottom: 10vh; padding: 1.8rem 1.6rem; max-width: 100%; }
        .rail { right: 0.5rem; }
        .rail-dot { width: 26px; height: 26px; }
        .scene-bg { background-position: center 25%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .travel-layer { display: none; }
    }
</style>
@endsection

@section('content')
@php
    /* Six fixed branches — each station arrives at a real photograph
       of a garment hanging in the banyan tree (demo/Dress). */
    $branches = [
        ['name' => 'Women',          'slug' => 'women',          'verse' => 'Where grace is woven thread by thread, and every drape remembers her.',   'photo' => 'images/dress/DAN09289.jpg', 'mirror' => false, 'side' => 'left'],
        ['name' => 'Men',            'slug' => 'men',            'verse' => 'Quiet strength, tailored in the shade of the old tree.',                  'photo' => 'images/dress/DAN09368.jpg', 'mirror' => false, 'side' => 'right'],
        ['name' => 'Kids',           'slug' => 'kids',           'verse' => 'Little branches reaching for the light, dressed in wonder.',              'photo' => 'images/dress/DAN09287.jpg', 'mirror' => true,  'side' => 'left'],
        ['name' => 'Dresses',        'slug' => 'dresses',        'verse' => 'Hung upon the bough like blossoms — each one waiting for a breeze.',      'photo' => 'images/dress/DAN09287.jpg', 'mirror' => false, 'side' => 'right'],
        ['name' => 'Home Decor',     'slug' => 'home-decor',     'verse' => 'Carry the calm of the canopy into every corner of your home.',            'photo' => 'images/dress/DAN09370.jpg', 'mirror' => false, 'side' => 'left'],
        ['name' => 'Eid Collection', 'slug' => 'eid-collection', 'verse' => 'For the mornings that shimmer — festive threads for cherished days.',     'photo' => 'images/dress/DAN09289.jpg', 'mirror' => true,  'side' => 'right'],
    ];
@endphp

<div class="journey" id="journey">
    <div class="stage" id="stage">

        {{-- SCENE 0 : TREETOP INTRO --}}
        <div class="scene" data-scene="0">
            <div class="scene-bg" style="background-image:url('{{ asset('images/parallax/scene_intro.png') }}')"></div>
            <div class="scene-tint tint-intro"></div>
            <div class="scene-content">
                <div class="intro-inner">
                    <p class="intro-eyebrow">Raangalay</p>
                    <div class="gold-rule"></div>
                    <h1 class="intro-title">শেকড়ে ঐতিহ্যের রং</h1>
                    <p class="intro-sub mt-4">Board at the treetop. Six branches lie along the line — ride it down to the roots.</p>
                </div>
            </div>
        </div>

        {{-- SCENES 1–6 : BRANCH STATIONS — each arrives at a real photograph --}}
        @foreach($branches as $i => $b)
        <div class="scene" data-scene="{{ $i + 1 }}">
            <div class="scene-bg {{ $b['mirror'] ? 'mirror' : '' }}" style="background-image:url('{{ asset($b['photo']) }}')"></div>
            <div class="scene-tint tint-branch {{ $b['side'] === 'right' ? 'flip' : '' }}"></div>
            <div class="scene-content {{ $b['side'] === 'left' ? 'content-left' : 'content-right' }}">
                <div class="branch-card">
                    <p class="branch-index">Branch {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} — 06</p>
                    <h2 class="branch-name serif">{{ $b['name'] }}</h2>
                    <div class="branch-rule"></div>
                    <p class="branch-verse">{{ $b['verse'] }}</p>
                    <div class="mt-8">
                        <a href="{{ route('category.show', $b['slug']) }}" class="branch-link">
                            Enter the branch
                            <svg viewBox="0 0 24 10" fill="none" stroke="currentColor" stroke-width="1">
                                <path d="M0 5h22M18 1l4 4-4 4"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- SCENE 7 : THE TRUNK --}}
        <div class="scene" data-scene="7">
            <div class="scene-bg" style="background-image:url('{{ asset('images/parallax/scene_trunk.png') }}')"></div>
            <div class="scene-tint tint-trunk"></div>
            <div class="scene-content">
                <div class="trunk-inner">
                    <p class="trunk-verse">
                        “A tree does not hurry.<br>
                        It gathers a hundred years into its trunk<br>
                        and offers them as shade.”
                    </p>
                    <p class="trunk-sig">— The Weavers of Raangalay</p>
                </div>
            </div>
        </div>

        {{-- SCENE 8 : THE GROUND — journey's end --}}
        <div class="scene" data-scene="8">
            <div class="scene-bg" style="background-image:url('{{ asset('images/parallax/scene_ground.png') }}')"></div>
            <div class="scene-tint tint-ground"></div>
            <div class="scene-content">
                <div class="ground-inner">
                    <p class="intro-eyebrow">You have reached the ground</p>
                    <div class="gold-rule"></div>
                    <h2 class="ground-title">Every journey ends<br>where the roots begin.</h2>
                    <p class="ground-sub">Handwoven. Hand-dyed. Held by heritage.</p>
                    <a href="{{ route('product.search') }}" class="ground-cta">
                        Shop the collection
                        <svg width="22" height="10" viewBox="0 0 24 10" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M0 5h22M18 1l4 4-4 4"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- TRAVEL LAYER : foliage rushing past between stations --}}
        <div class="travel-layer" id="travelLayer">
            <div class="travel-strip s3" style="background-image:url('{{ asset('images/parallax/scene_branch_deep.png') }}')"></div>
            <div class="travel-strip s2" style="background-image:url('{{ asset('images/parallax/scene_branch_mist.png') }}')"></div>
            <div class="travel-strip s1" style="background-image:url('{{ asset('images/parallax/branch_detail.png') }}')"></div>
            <div class="travel-streaks"></div>
        </div>

        {{-- Cinematic layers --}}
        <div class="film-grain"></div>
        <div class="stage-vignette"></div>

        {{-- Progress rail --}}
        <nav class="rail" id="rail" aria-label="Journey progress">
            @php
                $railLabels = array_merge(['Treetop'], array_column($branches, 'name'), ['The Trunk', 'The Ground']);
            @endphp
            @foreach($railLabels as $ri => $label)
                @if($ri > 0)<div class="rail-line"></div>@endif
                <button type="button" class="rail-dot {{ $ri === 0 ? 'active' : '' }}" data-goto="{{ $ri }}" aria-label="{{ $label }}">
                    <span class="rail-label">{{ $label }}</span>
                </button>
            @endforeach
        </nav>

        {{-- Scroll hint --}}
        <div class="scroll-hint" id="scrollHint">
            <span>Descend</span>
            <div class="thread"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    'use strict';

    var SCENES = 9;              // intro + 6 branch stations + trunk + ground
    var SCENE_SCROLL = 1.8;      // viewport-heights of scroll per station
    var journey = document.getElementById('journey');
    var stage = document.getElementById('stage');
    var scenes = Array.prototype.slice.call(stage.querySelectorAll('.scene'));
    var travelLayer = document.getElementById('travelLayer');
    var strips = {
        s1: travelLayer.querySelector('.s1'),
        s2: travelLayer.querySelector('.s2'),
        s3: travelLayer.querySelector('.s3')
    };
    var scrollHint = document.getElementById('scrollHint');
    var railDots = Array.prototype.slice.call(document.querySelectorAll('.rail-dot'));
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function sizeTrack() {
        var vh = window.innerHeight;
        journey.style.height = ((SCENES - 1) * SCENE_SCROLL * vh + vh) + 'px';
    }
    sizeTrack();
    window.addEventListener('resize', sizeTrack);

    var clamp = function (v, a, b) { return Math.max(a, Math.min(b, v)); };
    var ease = function (t) { return t * t * (3 - 2 * t); };           // smoothstep
    var easeOut = function (t) { return 1 - Math.pow(1 - t, 3); };     // decelerate (arriving)
    var easeIn = function (t) { return t * t * t; };                   // accelerate (departing)

    var activeIndex = -1;
    var vw = window.innerWidth;
    window.addEventListener('resize', function () { vw = window.innerWidth; });

    /* ------------------------------------------------------------
       THE BRANCH LINE
       The camera always travels in ONE direction (rightward along
       the tree, gently descending). Stations slide through the
       frame right -> left, like watching from a train window:

         arriving : station glides in from the RIGHT, decelerating,
                    unblurring — settles centred.
         holding  : gentle drift keeps the ride alive; card fades in.
         departing: station accelerates off to the LEFT, blurring.

       Between stations the travel layer (blurred foliage of the
       same tree) rushes across, so the next photograph reads as
       another branch further down the same line.
       ------------------------------------------------------------ */
    function render() {
        var rect = journey.getBoundingClientRect();
        var total = journey.offsetHeight - window.innerHeight;
        var progress = clamp(-rect.top / total, 0, 1);
        var sceneFloat = progress * (SCENES - 1);

        scenes.forEach(function (scene, i) {
            var t = sceneFloat - i;   // 0 = camera stopped at this station
            var bg = scene.querySelector('.scene-bg');
            var content = scene.querySelector('.scene-content');

            if (t < -0.62 || t > 0.62) {
                scene.style.opacity = 0;
                scene.classList.remove('is-active');
                return;
            }
            scene.classList.toggle('is-active', Math.abs(t) < 0.5);

            var shiftX, blur, opacity, scale;

            if (t <= 0) {
                /* ARRIVING — glides in from the right, decelerating */
                var q = easeOut(clamp((t + 0.62) / 0.62, 0, 1));  // 0 far right, 1 settled
                shiftX = (1 - q) * vw * 0.85;
                blur = (1 - q) * 16;
                opacity = clamp(q * 1.6, 0, 1);
                scale = 1.04;
            } else {
                /* DEPARTING — accelerates away to the left */
                var p = easeIn(clamp(t / 0.62, 0, 1));            // 0 settled, 1 gone left
                shiftX = -p * vw * 0.95;
                blur = p * 18;
                opacity = clamp(1 - p * 1.35, 0, 1);
                scale = 1.04;
            }

            /* HOLD drift — the ride never fully stops; the frame keeps
               creeping leftward slightly, motion continuity with travel */
            var hold = 1 - clamp(Math.abs(t) / 0.5, 0, 1);
            var drift = -t * vw * 0.06 * hold;

            if (reduceMotion) { shiftX = 0; blur = 0; drift = 0; }

            scene.style.opacity = opacity.toFixed(3);
            bg.style.transform =
                'translate3d(' + (shiftX + drift).toFixed(1) + 'px, ' + (-(t) * 30 * hold).toFixed(1) + 'px, 0)' +
                ' scale(' + scale + ')' +
                (bg.classList.contains('mirror') ? ' scaleX(-1)' : '');
            bg.style.filter = 'blur(' + blur.toFixed(1) + 'px)';

            /* Card appears only once the camera has fully arrived */
            if (content) {
                var settled = ease(clamp(1 - Math.abs(t) / 0.3, 0, 1));
                var fromRight = content.classList.contains('content-right');
                var slide = (1 - settled) * (fromRight ? 60 : -60);
                content.style.opacity = settled.toFixed(3);
                content.style.transform = 'translateX(' + slide.toFixed(1) + 'px)';
            }
        });

        /* ---- TRAVEL LAYER between stations ----
           frac 0.5 = midway between two stations. The foliage strips
           sweep right -> left at three parallax speeds, with a slight
           downward drift (the line descends the tree). */
        var frac = sceneFloat % 1;
        var mid = clamp(1 - Math.abs(frac - 0.5) / 0.34, 0, 1);
        var rush = ease(mid);

        if (!reduceMotion) {
            /* strip travel position: sweeps a full pass per transition */
            var sweep = (frac - 0.5) * 2;   // -1 .. 1 across the transition
            var down = sceneFloat * 24;      // constant gentle descent

            strips.s1.style.transform = 'translate3d(' + (-sweep * vw * 1.05) + 'px, ' + (down % 60) + 'px, 0) scale(1.15)';
            strips.s2.style.transform = 'translate3d(' + (-sweep * vw * 0.7) + 'px, ' + (down % 40) + 'px, 0) scale(1.2) scaleX(-1)';
            strips.s3.style.transform = 'translate3d(' + (-sweep * vw * 0.45) + 'px, ' + (down % 30) + 'px, 0) scale(1.25)';
            travelLayer.style.opacity = (rush * 0.92).toFixed(3);
        }

        /* Rail + hint */
        var idx = Math.round(sceneFloat);
        if (idx !== activeIndex) {
            activeIndex = idx;
            railDots.forEach(function (d, di) { d.classList.toggle('active', di === idx); });
        }
        scrollHint.style.opacity = progress > 0.02 ? 0 : 1;

        ticking = false;
    }

    var ticking = false;
    window.addEventListener('scroll', function () {
        if (!ticking) { requestAnimationFrame(render); ticking = true; }
    }, { passive: true });

    /* Rail navigation */
    railDots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            var i = parseInt(dot.dataset.goto, 10);
            var vh = window.innerHeight;
            var top = journey.offsetTop + i * SCENE_SCROLL * vh;
            window.scrollTo({ top: top, behavior: 'smooth' });
        });
    });

    /* Ambient dust motes */
    function spawnMote() {
        var m = document.createElement('div');
        m.className = 'mote';
        var s = 2 + Math.random() * 4;
        m.style.width = s + 'px';
        m.style.height = s + 'px';
        m.style.left = Math.random() * 100 + '%';
        m.style.bottom = '-10px';
        m.style.animationDuration = (9 + Math.random() * 14) + 's';
        stage.appendChild(m);
        setTimeout(function () { m.remove(); }, 24000);
    }
    if (!reduceMotion) {
        for (var i = 0; i < 6; i++) setTimeout(spawnMote, i * 700);
        setInterval(spawnMote, 2600);
    }

    render();
})();
</script>
@endsection
