{{-- resources/views/public/admission.blade.php --}}
@extends('layouts.site')

@section('title', 'Admission')

@section('content')
    {{-- PAGE HEADER --}}
    <section class="bg-transparent">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <p class="text-xs font-medium tracking-wide text-tpc-primary uppercase">Admission</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-tpc-ink sm:text-4xl">
                Enrollment & Requirements
            </h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-tpc-ink/70">
                Learn the enrollment steps and prepare the needed documents to become a student of Talibon Polytechnic College.
            </p>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="#requirements"
                   class="inline-flex items-center justify-center rounded-lg bg-tpc-primary px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-tpc-secondary">
                    View Requirements
                </a>
                <a href="#process"
                   class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary shadow-sm transition hover:bg-tpc-primary/5">
                    Enrollment Process
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center justify-center rounded-lg px-2 py-3 text-sm font-medium text-tpc-ink/80 transition hover:text-tpc-primary">
                    Need help? Contact us →
                </a>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="bg-transparent">
        <div class="max-w-7xl mx-auto px-4 pb-20">
            <div class="grid gap-6 lg:grid-cols-3">
                {{-- LEFT MAIN --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Requirements --}}
                    <div id="requirements" class="scroll-mt-20 rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-semibold text-tpc-ink">Admission Requirements</h2>
                                <p class="mt-1 text-sm text-tpc-ink/70">
                                    Bring original copies and photocopies (as applicable).
                                </p>
                            </div>
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-tpc-accent/30 text-tpc-secondary">
                                📄
                            </span>
                        </div>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl border border-tpc-primary/10 bg-tpc-primary/5 p-4">
                                <p class="text-sm font-semibold text-tpc-ink">For Freshmen</p>
                                <ul class="mt-3 list-disc pl-5 text-sm text-tpc-ink/80 space-y-1">
                                    <li>Report Card (Form 138)</li>
                                    <li>Good Moral Certificate</li>
                                    <li>PSA Birth Certificate</li>
                                    <li>2x2 ID Pictures (recent)</li>
                                    <li>Certificate of Completion (if available)</li>
                                </ul>
                            </div>

                            <div class="rounded-xl border border-tpc-primary/10 bg-tpc-primary/5 p-4">
                                <p class="text-sm font-semibold text-tpc-ink">For Transferees</p>
                                <ul class="mt-3 list-disc pl-5 text-sm text-tpc-ink/80 space-y-1">
                                    <li>Transcript of Records / Copy of Grades</li>
                                    <li>Honorable Dismissal</li>
                                    <li>Good Moral Certificate</li>
                                    <li>PSA Birth Certificate</li>
                                    <li>2x2 ID Pictures (recent)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-5 rounded-xl border border-tpc-primary/10 bg-white p-4">
                            <p class="text-sm font-medium text-tpc-ink">Note</p>
                            <p class="mt-1 text-sm text-tpc-ink/70">
                                Requirements may vary depending on the program. For the latest and official list,
                                please confirm with the admissions office.
                            </p>
                        </div>
                    </div>

                    {{-- Process --}}
                    <div id="process" class="scroll-mt-20 rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-semibold text-tpc-ink">Enrollment Process</h2>
                                <p class="mt-1 text-sm text-tpc-ink/70">
                                    Simple step-by-step enrollment guide.
                                </p>
                            </div>
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-tpc-accent/30 text-tpc-secondary">
                                ✅
                            </span>
                        </div>

                        <ol class="mt-5 space-y-4">
                            <li class="rounded-xl border border-tpc-primary/10 bg-white p-4">
                                <p class="font-medium text-tpc-ink">1) Prepare your requirements</p>
                                <p class="mt-1 text-sm text-tpc-ink/70">Complete the documents listed above.</p>
                            </li>
                            <li class="rounded-xl border border-tpc-primary/10 bg-white p-4">
                                <p class="font-medium text-tpc-ink">2) Submit documents to Admissions</p>
                                <p class="mt-1 text-sm text-tpc-ink/70">Proceed to the admissions office for screening.</p>
                            </li>
                            <li class="rounded-xl border border-tpc-primary/10 bg-white p-4">
                                <p class="font-medium text-tpc-ink">3) Choose your program</p>
                                <p class="mt-1 text-sm text-tpc-ink/70">Select the program that fits your goals.</p>
                            </li>
                            <li class="rounded-xl border border-tpc-primary/10 bg-white p-4">
                                <p class="font-medium text-tpc-ink">4) Assessment and payment (if applicable)</p>
                                <p class="mt-1 text-sm text-tpc-ink/70">Follow the cashier/assessment instructions.</p>
                            </li>
                            <li class="rounded-xl border border-tpc-primary/10 bg-white p-4">
                                <p class="font-medium text-tpc-ink">5) Confirm enrollment</p>
                                <p class="mt-1 text-sm text-tpc-ink/70">Receive confirmation and final instructions.</p>
                            </li>
                        </ol>
                    </div>

                    {{-- CTA --}}
                    <div class="tpc-card p-6">
                        <h2 class="text-xl font-semibold text-tpc-ink">Explore programs before enrolling</h2>
                        <p class="mt-2 text-sm text-tpc-ink/70">
                            View all academic programs offered by Talibon Polytechnic College.
                        </p>
                        <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('academics') }}"
                               class="inline-flex items-center justify-center rounded-lg bg-tpc-primary px-5 py-3 text-sm font-medium text-white hover:bg-tpc-secondary">
                                View Programs
                            </a>
                            <a href="{{ route('news.index') }}"
                               class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                                Latest Updates →
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDEBAR --}}
                <aside class="space-y-6">
                    <div class="tpc-card p-6">
                        <h3 class="text-lg font-semibold text-tpc-ink">Office Hours</h3>
                        <p class="mt-2 text-sm text-tpc-ink/70">Example only — replace with your official hours.</p>

                        <div class="mt-4 space-y-2 text-sm text-tpc-ink/80">
                            <div class="flex items-center justify-between">
                                <span>Monday – Friday</span><span class="font-medium">8:00 AM – 5:00 PM</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Saturday</span><span class="font-medium">8:00 AM – 12:00 PM</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Sunday</span><span class="font-medium">Closed</span>
                            </div>
                        </div>

                        <div class="mt-5 rounded-xl bg-tpc-primary/5 p-4">
                            <p class="text-sm font-medium text-tpc-ink">Tip</p>
                            <p class="mt-1 text-sm text-tpc-ink/70">
                                If you’re unsure about requirements, message the office first.
                            </p>
                        </div>
                    </div>

                    <div class="tpc-card p-6">
                        <h3 class="text-lg font-semibold text-tpc-ink">Contact Admissions</h3>
                        <p class="mt-2 text-sm text-tpc-ink/70">
                            Reach out for enrollment concerns, schedule, and guidance.
                        </p>
                        <div class="mt-5">
                            <a href="{{ route('contact') }}"
                               class="inline-flex w-full items-center justify-center rounded-lg bg-tpc-primary px-5 py-3 text-sm font-medium text-white hover:bg-tpc-secondary">
                                Go to Contact Page
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
