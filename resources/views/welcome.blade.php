@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">News Article Backend Task</h2>

    <div class="card shadow rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Project Details</h4>
        </div>

        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">
                    The assignment I received is backend focused and works using commands and Queues.
                </li>
                <li class="list-group-item">
                    I have chosen 3 Platforms to pull the data from.
                    <ul>
                        <li>The Guardian</li>
                        <li>NewsAPI.org</li>
                        <li>NY Times</li>
                    </ul>
                </li>
                <li class="list-group-item">
                    Each of the above platform has its own command that gets executed on daily basis<br>
                    And they generate payload to be pushed in Queue and consumed later on. <br>
                    Run the following commands to generate the payloads manually This way you can test the commands without waiting for the cron job to run:
                    <ul>
                        <li><code>php artisan app:fetch-theguardian-article-command</code></li>
                        <li><code>php artisan app:fetch-newsapiorg-article-command</code></li>
                        <li><code>php artisan app:fetch-nytimes-articles-command</code></li>
                    </ul>
                </li>
                <li class="list-group-item">
                    The payloads are stored in the database and can be viewed in jobs table.
                </li>
                <li class="list-group-item">
                    In order to consume the payloads you have to run the queue and consume them which will start pulling the data and store it in the database<br>
                    Run the following command to start consuming the queue:
                    <ul>
                        <li><code>php artisan queue:work</code></li>
                    </ul>
                </li>
                <li class="list-group-item">
                    <label class="text-danger"><b>NOTE</b></label> The queue has a delay of 1 second and takes a lot of time to be consumed.
                </li>
                <li class="list-group-item">
                    <label class="text-danger"><b>NOTE</b></label> At first I was shared a wrong assignment which contained Swagger Docs as well as UI. Later the assignment I received didn't include UI and Swagger Docs. However, I have added them as part of the project.
                </li>
                <li class="list-group-item">
                    Although The UI was not request in the assignment, I have created a simple UI to view the articles. <a class="btn btn-success" href="/view-articles">Click to View Articles</a>
                </li>
                <li class="list-group-item">
                    The UI is basic html and css developed with ChatGPT using Bootstrap and contains some filters.
                </li>
                <li class="list-group-item">
                    I have also added Swagger documentation for the API endpoints. <a class="btn btn-info" href="/api/documentation">Click to View API Documentation</a>
                </li>
                <li class="list-group-item">
                    User Registration/Login/Forgot-Password functionality was also a part of the 1st assignment I received. But I have kept it there and you can find its API's in the docs.
                </li>
                <li class="list-group-item">
                    The Auth API's all work but I have not added auth check to any of the other API's however its simple as we can add already existing middleware to the routes.
                </li>
                <li class="list-group-item">
                    To test out Auth in Swagger use email: <code>user1@app.com</code> and password: <code>123123123</code>
                </li>
                <li class="list-group-item">
                    <label class="text-danger"><b>POINT</b></label> Below are some screenshots from locally working setup you can also view loom video <a class="btn btn-info" href="https://www.loom.com/share/04042f60f2ef42a88542fa94ca63a304?sid=c832265c-5308-4bed-8c98-142dd5c739ee" target="_blank">Click to View Loom</a> to see the working setup.
                </li>
            </ul>
        </div>
    </div>

    <br><br><br>

    <div id="carouselExampleIndicators" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('/images/pic1.png') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('/images/pic2.png') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('/images/pic3.png') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('/images/pic4.png') }}" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
@endsection
