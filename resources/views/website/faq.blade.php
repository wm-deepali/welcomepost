@extends('website.layout.layout')
@section('content')
<style>
    .new-div h2, .new-div h3 {
        text-align: center;
    }
    
    /* Optional: Center the entire content inside the login div using Flexbox */
    .new-div {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .blog-body {
        padding-top: 5rem;
    }
    
    .login-main {
        position: relative;
    }
    
    .new-div {
        text-align: center;
    }
    
    .new-div h2 {
        margin-bottom: 15px;
    }
    
    .how-to-coll {
        text-align: left;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f9f9f9;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .new-div h2 {
            font-size: 1.5rem;
        }
    
        .new-div h3 {
            font-size: 1.25rem;
        }
    
        .new-div p {
            font-size: 0.875rem;
        }
    
        .how-to-coll {
            text-align: center;
        }
    
        .col-md-12 {
            padding: 0 15px;
            margin-bottom: 20px;
        }
    }
</style>
<!-- Preloader -->
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>

<section class="blog-body pt-5">
    <div class="container">
        <div class="row">
            <div class="login-main add-list posr">
                <div class="log log-1">
                    <div class="new-div">
                        <h2 style="margin-bottom:15px;">Frequently Asked Questions</h2>
                        @foreach($faqCategories as $category)
                            <div class="col-md-12" style="margin-bottom:20px;">
                                <div class="how-to-coll">
                                    <h3>{{ $category->name }}</h3>
                                    <ul>
                                        @foreach($category->faqs as $faq)
                                            <li>
                                                <h4 class="question">{{ $faq->question }}</h4>
                                                <div>
                                                    <p>{{ $faq->answer }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection