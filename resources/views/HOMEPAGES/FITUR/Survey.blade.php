<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Pelayanan</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>

        :root {
            --black: #000000;
            --white: #ffffff;
            --dark: #0a0a0a;
            --dark-card: #2a2a2a;
            --border: #3a3a3a;
            --text: #e0e0e0;
            --muted: #9fb3b5;
            --teal: #f1991694;
            --teal-2: rgb(212, 172, 90);
            --radius: 18px;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Roboto',sans-serif;
            background:var(--black);
            color:var(--white);
            min-height:100vh;
        }

        .container{
            width:100%;
            max-width:750px;
            margin:auto;
            padding:40px 20px;
        }

        .survey-card{
            background:var(--dark);
            border:1px solid var(--border);
            border-radius:var(--radius);
            overflow:hidden;
        }

        .survey-header{
            display:flex;
            align-items:center;
            gap:15px;
            padding:25px;
            border-bottom:1px solid var(--border);
        }

        .survey-header img{
            width:60px;
            height:60px;
            object-fit:contain;
        }

        .survey-title h1{
            font-size:1.5rem;
            margin-bottom:4px;
        }

        .survey-title p{
            color:var(--muted);
            font-size:.95rem;
        }

        .survey-body{
            padding:30px;
        }

        .question{
            margin-bottom:40px;
        }

        .question h3{
            font-size:1.05rem;
            margin-bottom:10px;
            line-height:1.5;
        }

        .question p{
            color:var(--muted);
            margin-bottom:18px;
            font-size:.9rem;
        }

        .checkbox-group{
            display:flex;
            flex-direction:column;
            gap:14px;
        }

        .checkbox-item{
            display:flex;
            align-items:center;
            gap:12px;
            padding:14px;
            border:1px solid var(--border);
            border-radius:14px;
            transition:.3s;
            cursor:pointer;
        }

        .checkbox-item:hover{
            border-color:var(--teal-2);
            background:rgba(255,255,255,0.03);
        }

        input[type="checkbox"],
        input[type="radio"]{
            accent-color:var(--teal-2);
            width:18px;
            height:18px;
        }

        .rating-group{
            display:grid;
            grid-template-columns:repeat(5,1fr);
            gap:12px;
        }

        .rating-item{
            border:1px solid var(--border);
            border-radius:14px;
            text-align:center;
            padding:16px 10px;
            transition:.3s;
            cursor:pointer;
        }

        .rating-item:hover{
            border-color:var(--teal-2);
            background:rgba(255,255,255,0.03);
        }

        .rating-item input{
            margin-bottom:10px;
        }

        textarea{
            width:100%;
            min-height:160px;
            background:var(--dark-card);
            border:1px solid var(--border);
            border-radius:14px;
            padding:18px;
            color:var(--white);
            font-size:.95rem;
            resize:none;
        }

        textarea:focus{
            outline:none;
            border-color:var(--teal-2);
        }

        .btn-submit{
            width:100%;
            border:none;
            background:var(--teal-2);
            color:var(--black);
            padding:16px;
            border-radius:14px;
            font-size:1rem;
            font-weight:700;
            cursor:pointer;
            transition:.3s;
        }

        .btn-submit:hover{
            opacity:.9;
            transform:translateY(-2px);
        }

        .alert-success{
            background:#16351f;
            border:1px solid #2f8f46;
            color:#8ff0a4;
            padding:16px;
            border-radius:14px;
            margin-bottom:20px;
        }

        @media(max-width:768px){

            .survey-header{
                align-items:flex-start;
            }

            .rating-group{
                grid-template-columns:repeat(2,1fr);
            }

            .survey-body{
                padding:20px;
            }

        }

    </style>
</head>
<body>

<div class="container">

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="survey-card">

        <div class="survey-header">

            {{-- GANTI LOGO --}}
            <img src="{{asset('public/asset/PICTURESET/LOGOSPDASHBOARD.png') }}" alt="Logo" class="logo-img">

            <div class="survey-title">
                <h1>Survey Pelayanan</h1>
                <p>Bantu kami menjadi lebih baik lagi ✨</p>
            </div>

        </div>

        <div class="survey-body">

            <form action="{{ route('SurveyStore') }}" method="POST">
                @csrf

                {{-- QUESTION 1 --}}
                <div class="question">

                    <h3>
                        1. Dari semua layanan kami, mana yang paling kamu suka pelayanannya?
                    </h3>

                    <p>(Boleh pilih lebih dari satu ya)</p>

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

                                <input type="checkbox"
                                       name="favorite_services[]"
                                       value="{{ $service }}">

                                <span>{{ $service }}</span>

                            </label>

                        @endforeach

                    </div>

                </div>

                {{-- QUESTION 2 --}}
                <div class="question">

                    <h3>
                        2. Seberapa besar kemungkinan kamu merekomendasikan Studio Prewed ke teman, kerabat, atau keluarga?
                    </h3>

                    <p>(Skala 1–10)</p>

                    <div class="rating-group">

                        @for($i = 1; $i <= 10; $i++)

                            <label class="rating-item">

                                <input type="radio"
                                       name="recommendation_score"
                                       value="{{ $i }}"
                                       required>

                                <div>{{ $i }}</div>

                            </label>

                        @endfor

                    </div>

                </div>

                {{-- QUESTION 3 --}}
                <div class="question">

                    <h3>
                        3. Ada hal yang kurang berkenan atau yang bisa kami tingkatkan ke depannya?
                    </h3>

                    <textarea
                        name="feedback"
                        placeholder="Tulis masukan kamu di sini..."
                    ></textarea>

                </div>

                <button type="submit" class="btn-submit">
                    Kirim Survey
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>