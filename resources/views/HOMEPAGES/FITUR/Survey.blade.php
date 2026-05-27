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
                            Terima kasih sudah mempercayakan cerita kalian kepada kami
                        </h1>

                        <p>
                            Kami ingin pengalaman setiap couple terus jadi lebih baik ✨
                        </p>

                    </div>

                </div>

                {{-- BODY --}}
                <div class="survey-body">

                    <form action="{{ route('SurveyStore') }}" method="POST">
                        @csrf

                        {{-- CUSTOMER --}}
                        <div class="survey-question">

                            <h3>
                                Informasi Customer
                            </h3>

                            <div class="form-grid">

                                <div class="input-group">
                                    <label>
                                        Nama Customer
                                    </label>

                                    <input
                                        type="text"
                                        name="customer_name"
                                        placeholder="Masukkan nama customer"
                                    >
                                </div>

                                <div class="input-group">
                                    <label>
                                        Tanggal Foto
                                    </label>

                                    <input
                                        type="date"
                                        name="photo_date"
                                    >
                                </div>

                            </div>

                        </div>
                        <div class="survey-question">

                            <h3>
                                1. Seberapa besar kemungkinan kamu merekomendasikan Studio Prewed ke teman, kerabat, atau keluarga?
                            </h3>

                            <p>
                                (Rating 1–10)
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
                        
                        <div class="survey-question">

                            <h3>
                                2. Apa yang paling membantu dari layanan kami?
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
                                        'Attire ( Busana )',
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

                        <div class="survey-question">
                            <h3>
                                3. Kedepannya kalian tertarik layanan apa?
                            </h3>
                            <p>
                                (Boleh pilih lebih dari satu)
                            </p>
                            <div class="checkbox-group">
                                @php
                                    $futureServices = [

                                        'Post Wedding',
                                        'Maternity',
                                        'Family Portrait',
                                        'Anniversary Session'

                                    ];
                                @endphp
                                @foreach($futureServices as $futureService)
                                    <label class="checkbox-item">
                                        <input
                                            type="checkbox"
                                            name="future_services[]"
                                            value="{{ $futureService }}"
                                        >
                                        <span>
                                            {{ $futureService }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="survey-question">

                            <h3>
                                4. Selama pengalaman bersama kami, adakah hal yang terasa kurang nyaman atau bisa kami buat lebih baik lagi?
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