@extends('layouts.app')

@section('title', 'DMCA Policy - Balyan')

@push('styles')
    <style>
        /* Page Header */
        .dmca-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            padding: 4rem 0 2.5rem;
            color: #fff;
            text-align: center;
        }

        /* Ensure left alignment for English content */
        .dmca-card, .policy-list, .policy-list ul {
            text-align: left;
        }

        .policy-list {
            padding-left: 1.5rem;
            padding-right: 0;
            direction: ltr; /* اضافه شده برای اطمینان از چپ‌چین بودن */
            text-align: left; /* اضافه شده برای اطمینان از چپ‌چین بودن */
        }

        .policy-list li {
            margin-bottom: 1rem;
            line-height: 1.6;
            text-align: left; /* اضافه شده برای چپ‌چین کردن */
            direction: ltr; /* اضافه شده برای اطمینان از چپ‌چین بودن */
        }

        .policy-list ol, .policy-list ul {
            margin-bottom: 1rem;
            text-align: left; /* اضافه شده برای چپ‌چین کردن */
            direction: ltr; /* اضافه شده برای اطمینان از چپ‌چین بودن */
        }

        .dmca-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .dmca-hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .dmca-hero-desc {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .dmca-hero-update {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* Sections */
        .dmca-section {
            margin-bottom: 3rem;
            scroll-margin-top: 110px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.2rem;
            text-align: left; /* اضافه شده برای چپ‌چین کردن */
            flex-direction: row-reverse; /* اضافه شده برای معکوس کردن ترتیب آیکون و متن */
        }

        .section-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            margin-right: 1rem; /* تغییر از margin-left به margin-right */
            margin-left: 0; /* اضافه شده برای اطمینان */
            flex-shrink: 0;
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        .dmca-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            text-align: left; /* اضافه شده برای چپ‌چین کردن */
        }

        .info-highlight {
            background-color: rgba(59, 130, 246, 0.05);
            border-right: 4px solid var(--primary);
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            text-align: left; /* اضافه شده برای چپ‌چین کردن */
        }

        /* Footer Section */
        .dmca-footer {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
        }

        .dmca-footer i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .dmca-footer h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .dmca-footer p {
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
            direction: ltr;
        }

        /* تمام پاراگراف‌ها در این صفحه چپ‌چین باشند */
        .dmca-card p {
            text-align: left;
            direction: ltr;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dmca-hero-title {
                font-size: 1.8rem;
            }

            .section-icon {
                width: 38px;
                height: 38px;
                font-size: 1.1rem;
            }

            .section-header h2 {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .section-header {
                flex-direction: column;
                text-align: left; /* تغییر از center به left */
            }

            .section-icon {
                margin: 0 auto 0.8rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- DMCA Policy Page Header -->
    <div class="dmca-hero">
        <div class="container">
            <div class="text-center">
                <i class="fas fa-copyright dmca-hero-icon"></i>
                <h1 class="dmca-hero-title">DMCA Notice</h1>
                <p class="dmca-hero-desc">Digital Millennium Copyright Act (DMCA) Compliance Statement</p>
                <div class="dmca-hero-update">
                    <strong>Last Updated:</strong> Monday, March 17, 2025
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- DMCA Notice Section -->
                <section class="dmca-section">
                    <div class="section-header">
                        <i class="fas fa-balance-scale section-icon"></i>
                        <h2>DMCA Compliance Statement</h2>
                    </div>
                    <div class="dmca-card">
                        <p>At <strong>Balyan.ir</strong>, we respect the intellectual property rights of others and are fully committed to complying with the <strong>Digital Millennium Copyright Act (DMCA)</strong>. Our platform allows users to discover, purchase, and access books while adhering to copyright laws.</p>

                        <p>If you believe that your copyrighted work has been used on our website in a manner that constitutes copyright infringement, please notify our <strong>designated DMCA Agent</strong> using the process outlined below. We will review and respond to all valid requests in accordance with the DMCA.</p>
                    </div>
                </section>

                <!-- How to File a DMCA Notice -->
                <section class="dmca-section">
                    <div class="section-header">
                        <i class="fas fa-file-alt section-icon"></i>
                        <h2>How to File a DMCA Notice</h2>
                    </div>
                    <div class="dmca-card">
                        <p>To submit a <strong>DMCA notice</strong>, you must provide a written request containing the following details:</p>
                        <ol class="policy-list">
                            <li>
                                <strong>Your Contact Information</strong>
                                <ul>
                                    <li>Full name</li>
                                    <li>Physical address</li>
                                    <li>Telephone number</li>
                                    <li>Email address</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Details of the Copyrighted Work</strong>
                                <ul>
                                    <li>Title and author</li>
                                    <li>A detailed description of the copyrighted content</li>
                                    <li>If applicable, a URL where the copyrighted work is located on our website</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Identification of the Infringing Material</strong>
                                <ul>
                                    <li>The exact location (URL) of the infringing content on our website</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Good-Faith Statement</strong>
                                <ul>
                                    <li>A declaration stating that you <strong>believe in good faith</strong> that the use of the copyrighted material is unauthorized by the copyright owner, its agent, or the law.</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Accuracy and Authorization Statement</strong>
                                <ul>
                                    <li>A statement, made <strong>under penalty of perjury</strong>, confirming that the information in your notice is accurate and that you are either the copyright owner or have authorization to act on behalf of the copyright owner.</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Signature</strong>
                                <ul>
                                    <li>A physical or electronic signature of the copyright owner or their authorized representative.</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                </section>

                <!-- DMCA Agent Contact Information -->
                <section class="dmca-section">
                    <div class="section-header">
                        <i class="fas fa-envelope section-icon"></i>
                        <h2>Submit Your DMCA Notice to:</h2>
                    </div>
                    <div class="dmca-card">
                        <div class="info-highlight">
                            <p><strong>DMCA Agent:</strong> Balyan</p>
                            <p>📍 <strong>Address:</strong> Fars – Kazerun</p>
                            <p>📧 <strong>Email:</strong> balyan.ir@gmail.com</p>
                            <p>📞 <strong>Phone:</strong> +98 714 236 3111</p>
                        </div>
                    </div>
                </section>

                <!-- Counter-Notification Section -->
                <section class="dmca-section">
                    <div class="section-header">
                        <i class="fas fa-reply section-icon"></i>
                        <h2>Counter-Notification (Challenging a Removal)</h2>
                    </div>
                    <div class="dmca-card">
                        <p>If you believe that your content was wrongfully removed due to a mistaken copyright claim, you have the right to submit a <strong>counter-notification</strong> to our DMCA Agent. Your counter-notification must include:</p>
                        <ol class="policy-list">
                            <li>
                                <strong>Your Contact Information</strong>
                                <ul>
                                    <li>Full name</li>
                                    <li>Physical address</li>
                                    <li>Telephone number</li>
                                    <li>Email address</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Details of the Removed Content</strong>
                                <ul>
                                    <li>Identification of the material that was removed</li>
                                    <li>The URL where the content was previously located before removal</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Good-Faith Statement</strong>
                                <ul>
                                    <li>A declaration under <strong>penalty of perjury</strong> stating that you believe the removal was due to <strong>mistake or misidentification</strong>.</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Consent to Legal Jurisdiction</strong>
                                <ul>
                                    <li>A statement agreeing to the jurisdiction of the <strong>Federal District Court</strong> where your address is located, or if outside the U.S., the <strong>United States District Court for the Northern District of California</strong>.</li>
                                    <li>A statement confirming that you accept legal service from the original complainant or their agent.</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                </section>

                <!-- Submission and Removal Process -->
                <section class="dmca-section">
                    <div class="section-header">
                        <i class="fas fa-trash-alt section-icon"></i>
                        <h2>Removal of Infringing Material</h2>
                    </div>
                    <div class="dmca-card">
                        <p>Upon receiving a valid <strong>DMCA notice</strong>, we will promptly remove or disable access to the infringing content. We will also notify the alleged infringer of the takedown request. <strong>Repeat infringers may have their accounts terminated.</strong></p>
                    </div>
                </section>

                <!-- Policy Updates -->
                <section class="dmca-section">
                    <div class="section-header">
                        <i class="fas fa-sync section-icon"></i>
                        <h2>Updates to This DMCA Policy</h2>
                    </div>
                    <div class="dmca-card">
                        <p>We may update this <strong>DMCA Notice</strong> from time to time to maintain compliance with applicable laws. Any changes will be posted on this page.</p>
                        <p>If you have any questions regarding our <strong>DMCA compliance</strong>, please contact our designated DMCA Agent.</p>
                    </div>
                </section>

                <!-- Footer Section -->
                <div class="dmca-footer">
                    <i class="fas fa-copyright"></i>
                    <h3>Our Commitment to Intellectual Property Rights</h3>
                    <p>Balyan is committed to protecting the intellectual property rights of authors and publishers, and to maintaining the highest standards of copyright compliance.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
