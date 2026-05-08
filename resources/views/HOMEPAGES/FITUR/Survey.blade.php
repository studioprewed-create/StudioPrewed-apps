<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Kepuasan Pelanggan</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/LAYOUT/Base.css') }}">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/FITUR/survey.css') }}">
</head>
<body>

<section class="survey-page">

    <div class="container">

        <div class="survey-wrap">

            @if(session('success'))
                <div class="survey-alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="survey-card">

                {{-- HEADER --}}
                <div class="survey-header">

                    <img 
                        src="{{ asset('public/asset/PICTURESET/LOGOSPDASHBOARD.png') }}"
                        alt="Logo"
                        class="logo-img"
                    >

                    <div class="survey-title">

                        <span class="eyebrow">
                            CUSTOMER FEEDBACK
                        </span>

                        <h1>
                            Survey Kepuasan Pelanggan
                        </h1>

                        <p>
                            Bantu kami menjadi lebih baik lagi ✨
                        </p>

                    </div>

                </div>

                {{-- BODY --}}
                <div class="survey-body">

                    <form action="{{ route('SurveyStore') }}" method="POST">
                        @csrf

                        {{-- QUESTION 1 --}}
                        <div class="survey-question">

                            <h3>
                                1. Dari semua layanan kami, mana yang paling kamu suka pelayanannya?
                            </h3>

                            <p>
                                (Boleh pilih lebih dari satu ya)
                            </p>

                            <div class="checkbox-group">

                                @php
                                    $services = [
                                        'Fotografer',
                                        'Videografer',
                                        'MUA',
                                        'Admin Studio',
                                        'Tim Attire Studio',
                                        'Tim Fitting',
                                        'Admin Attire'
                                    ];
                                @endphp

                                @foreach($services as $service)

                                    <label class="checkbox-item">

                                        <input
                                            type="checkbox"
                                            name="favorite_services[]"
                                            value="{{ $service }}"
                                        >

                                        <span>
                                            {{ $service }}
                                        </span>

                                    </label>

                                @endforeach

                            </div>

                        </div>

                        {{-- QUESTION 2 --}}
                        <div class="survey-question">

                            <h3>
                                2. Seberapa besar kemungkinan kamu merekomendasikan Studio Prewed ke teman, kerabat, atau keluarga?
                            </h3>

                            <p>
                                (Skala 1–10)
                            </p>

                            <div class="rating-group">

                                @for($i = 1; $i <= 10; $i++)

                                    <label class="rating-item">

                                        <input
                                            type="radio"
                                            name="recommendation_score"
                                            value="{{ $i }}"
                                            required
                                        >

                                        <div>
                                            {{ $i }}
                                        </div>

                                    </label>

                                @endfor

                            </div>

                        </div>

                        {{-- QUESTION 3 --}}
                        <div class="survey-question">

                            <h3>
                                3. Ada hal yang kurang berkenan atau yang bisa kami tingkatkan ke depannya?
                            </h3>

                            <textarea
                                name="feedback"
                                placeholder="Tulis masukan kamu di sini..."
                            ></textarea>

                        </div>

                        <button type="submit" class="survey-submit">
                            Kirim Survey
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

</body>
</html>