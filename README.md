File Tree: tpc_website
Generated on: 5/8/2026, 5:24:03 PM
Root path: c:\Projects\Laravel\tpc_website

────────────────────────────────────────────────────────────────────────────────

├── app/
│ ├── Http/
│ │ ├── Controllers/
│ │ │ ├── Admin/
│ │ │ │ ├── ContactMessageController.php
│ │ │ │ ├── DashboardController.php
│ │ │ │ ├── NewsPostController.php
│ │ │ │ ├── ProgramController.php
│ │ │ │ ├── SettingController.php
│ │ │ │ └── UserController.php
│ │ │ ├── Auth/
│ │ │ │ ├── AuthenticatedSessionController.php
│ │ │ │ ├── ConfirmablePasswordController.php
│ │ │ │ ├── EmailVerificationNotificationController.php
│ │ │ │ ├── EmailVerificationPromptController.php
│ │ │ │ ├── NewPasswordController.php
│ │ │ │ ├── PasswordController.php
│ │ │ │ ├── PasswordResetLinkController.php
│ │ │ │ ├── RegisteredUserController.php
│ │ │ │ └── VerifyEmailController.php
│ │ │ ├── AcademicsController.php
│ │ │ ├── AdmissionController.php
│ │ │ ├── ContactController.php
│ │ │ ├── Controller.php
│ │ │ ├── HomeController.php
│ │ │ ├── NewsController.php
│ │ │ ├── ProfileController.php
│ │ │ └── UserController.php
│ │ ├── Middleware/
│ │ │ ├── AdminMiddleware.php
│ │ │ └── SuperAdminMiddleware.php
│ │ └── Requests/
│ │ ├── Auth/
│ │ │ └── LoginRequest.php
│ │ └── ProfileUpdateRequest.php
│ ├── Mail/
│ │ └── ContactMessageReceived.php
│ ├── Models/
│ │ ├── ContactMessage.php
│ │ ├── NewsPost.php
│ │ ├── Program.php
│ │ ├── SiteSetting.php
│ │ └── User.php
│ ├── Notifications/
│ │ └── TpcResetPasswordNotification.php
│ ├── Providers/
│ │ ├── AppServiceProvider.php
│ │ └── AuthServiceProvider.php
│ ├── Support/
│ │ └── Settings.php
│ └── View/
│ └── Components/
│ ├── AppLayout.php
│ └── GuestLayout.php
├── bootstrap/
│ ├── app.php
│ └── providers.php
├── config/
│ ├── app.php
│ ├── auth.php
│ ├── cache.php
│ ├── database.php
│ ├── filesystems.php
│ ├── logging.php
│ ├── mail.php
│ ├── queue.php
│ ├── services.php
│ └── session.php
├── database/
│ ├── factories/
│ │ └── UserFactory.php
│ ├── migrations/
│ │ ├── 0001_01_01_000000_create_users_table.php
│ │ ├── 0001_01_01_000001_create_cache_table.php
│ │ ├── 0001_01_01_000002_create_jobs_table.php
│ │ ├── 2026_01_31_120219_create_programs_table.php
│ │ ├── 2026_01_31_120242_create_news_posts_table.php
│ │ ├── 2026_01_31_120302_create_site_settings_table.php
│ │ ├── 2026_02_05_001650_create_contact_messages_table.php
│ │ └── 2026_02_05_003410_add_read_fields_to_contact_messages_table.php
│ ├── seeders/
│ │ ├── DatabaseSeeder.php
│ │ ├── NewsPostSeeder.php
│ │ ├── ProgramSeeder.php
│ │ ├── SiteSettingSeeder.php
│ │ └── SuperAdminSeeder.php
│ ├── .gitignore
│ └── database.sqlite
├── public/
│ ├── images/
│ │ ├── TPC-Logo.png
│ │ ├── favicon.ico
│ │ └── school-bg.jpg
│ ├── .htaccess
│ ├── index.php
│ └── robots.txt
├── resources/
│ ├── css/
│ │ └── app.css
│ ├── js/
│ │ ├── app.js
│ │ └── bootstrap.js
│ └── views/
│ ├── admin/
│ │ ├── messages/
│ │ │ ├── index.blade.php
│ │ │ └── show.blade.php
│ │ ├── news-posts/
│ │ │ ├── create.blade.php
│ │ │ ├── edit.blade.php
│ │ │ └── index.blade.php
│ │ ├── partials/
│ │ │ ├── header.blade.php
│ │ │ └── sidebar.blade.php
│ │ ├── programs/
│ │ │ ├── create.blade.php
│ │ │ ├── edit.blade.php
│ │ │ └── index.blade.php
│ │ ├── settings/
│ │ │ └── edit.blade.php
│ │ ├── users/
│ │ │ ├── create.blade.php
│ │ │ ├── edit.blade.php
│ │ │ └── index.blade.php
│ │ ├── dashboard.blade.php
│ │ └── layout.blade.php
│ ├── auth/
│ │ ├── confirm-password.blade.php
│ │ ├── forgot-password.blade.php
│ │ ├── login.blade.php
│ │ ├── register.blade.php
│ │ ├── reset-password.blade.php
│ │ └── verify-email.blade.php
│ ├── components/
│ │ ├── application-logo.blade.php
│ │ ├── auth-session-status.blade.php
│ │ ├── danger-button.blade.php
│ │ ├── dropdown-link.blade.php
│ │ ├── dropdown.blade.php
│ │ ├── input-error.blade.php
│ │ ├── input-label.blade.php
│ │ ├── modal.blade.php
│ │ ├── nav-link.blade.php
│ │ ├── primary-button.blade.php
│ │ ├── responsive-nav-link.blade.php
│ │ ├── secondary-button.blade.php
│ │ ├── text-input.blade.php
│ │ └── tpc-account-dropdown.blade.php
│ ├── emails/
│ │ ├── contact/
│ │ │ └── received.blade.php
│ │ └── reset-password.blade.php
│ ├── layouts/
│ │ ├── app.blade.php
│ │ ├── guest.blade.php
│ │ ├── navigation.blade.php
│ │ └── site.blade.php
│ ├── partials/
│ │ ├── footer.blade.php
│ │ └── nav.blade.php
│ ├── profile/
│ │ ├── partials/
│ │ │ ├── delete-user-form.blade.php
│ │ │ ├── update-password-form.blade.php
│ │ │ └── update-profile-information-form.blade.php
│ │ └── edit.blade.php
│ ├── public/
│ │ ├── news/
│ │ │ ├── index.blade.php
│ │ │ └── show.blade.php
│ │ ├── academics.blade.php
│ │ ├── admission.blade.php
│ │ ├── contact.blade.php
│ │ └── home.blade.php
│ ├── dashboard.blade.php
│ └── welcome1.blade.php
├── routes/
│ ├── auth.php
│ ├── console.php
│ └── web.php
├── storage/
│ ├── app/
│ │ ├── private/
│ │ │ └── .gitignore
│ │ ├── public/
│ │ │ ├── news-images/
│ │ │ │ ├── A1NnGfpkbMaZCr6mbv67QuzChicynfZgRVV7H7S3.jpg
│ │ │ │ ├── Ar7dlz0Mtw9FfjxCeMQBbDh28Ta4eRy9inkGvbOU.jpg
│ │ │ │ ├── HQmMBDgX1qBmeWWzxOsT8v59JxWZrz5mUktRlTZU.jpg
│ │ │ │ ├── ZB7e4WzNIhRhEuwWhbyicUozJcvzSuYhZQnDpnqf.jpg
│ │ │ │ └── tq3ugB1h6ihvA1l1f0SNdwS1s0IVappktDMiN9mi.jpg
│ │ │ ├── program-logos/
│ │ │ │ ├── CICyWJ2l928MLt6YM1huP9sxLeFyK4rhPudc805V.png
│ │ │ │ ├── FM2tpv1vjQX0DHjQOXNCXPsT4PWhEdbKPNBftLR4.png
│ │ │ │ ├── IFS4GgwIxCrkbS0EHd8cduqViJo7OTUntUxjN0NU.png
│ │ │ │ ├── TP0mrdECqYs5av3tu6Hne6errntn1MOezpUitYrn.png
│ │ │ │ ├── hsZYQ9mkO3PsS9VGVYwkhES2xbIW9yGGomwFsCfT.png
│ │ │ │ ├── u3pEWt4mKUcaSCUrUpxQTjO2Vsb5mzQ2uuARh0OK.png
│ │ │ │ └── u4Ev4p9XilI9IGfuTgSsMWKZHgnFo3BYG4Vg93Wp.png
│ │ │ └── .gitignore
│ │ └── .gitignore
│ ├── framework/
│ │ ├── sessions/
│ │ │ ├── .gitignore
│ │ │ └── 0mKZNYQozHB25YSkHHjHGI3bNNiLKqbAJR59IwYb
│ │ ├── testing/
│ │ │ └── .gitignore
│ │ ├── views/
│ │ │ ├── .gitignore
│ │ │ ├── 01a1ef9922a2a4b36ed134be98bcfe53.php
│ │ │ ├── 047f2d5ec34a8a0bdedae6cd96f4d0f5.php
│ │ │ ├── 05026ebe573e68bc2c8f2671f8557ce0.php
│ │ │ ├── 05b24672d15d4aaeca3f13367f5af18f.php
│ │ │ ├── 08da0e68971b8d9c7e24294a5273a7df.php
│ │ │ ├── 0943631556aa27f229d22bc168dd3cdb.php
│ │ │ ├── 0c11f5ecd8f0b0c3dfdbc51b944b7f84.php
│ │ │ ├── 0c6bb2b081ea73ee608b1a4ee59b33e8.php
│ │ │ ├── 0cd32f1f56e834d8fae2d2696b2a9eb3.php
│ │ │ ├── 0f26653205d91ff04d59bc1264492c4b.php
│ │ │ ├── 136c767e24b7a260896c8a77e5984f65.php
│ │ │ ├── 148fe1a0ecc6075acb1f577942607621.php
│ │ │ ├── 15130aadd0a3b8cea8491c9dfb0ce9b6.php
│ │ │ ├── 166520d167a2cc8d9e20b9d360840939.php
│ │ │ ├── 1ab6f642d606cbd46964e4dabead249c.php
│ │ │ ├── 20eab6c6e77115bff1b3d4ce4b7d9c2f.php
│ │ │ ├── 210299c6f9ae3d13d85c7de8e2b2e7db.php
│ │ │ ├── 37fcf2bd1e041c06d954712134c70533.php
│ │ │ ├── 3bc3fc684a6a276cc8a15ef17f11415f.php
│ │ │ ├── 4317128c233d9b3b0f66c984ce1a7613.php
│ │ │ ├── 491de05f3ae329714824b813f49a9421.php
│ │ │ ├── 4949a9143a306642fb85abad23a6ddc0.php
│ │ │ ├── 499871ea2e572da359cc3b4cec747066.php
│ │ │ ├── 4ba53395ab20237843c096acc15137ce.php
│ │ │ ├── 4f1ae2cfd08f5a2efa2791a902e0be3a.php
│ │ │ ├── 53fd22a794b48b46aa3c61a8c88fa5a1.php
│ │ │ ├── 54cd74d53673559357af3903e17ae40a.php
│ │ │ ├── 58f7ce3b0877d610ed847104832acf36.php
│ │ │ ├── 5c2cdbd658b82b92fd9406e72330b354.php
│ │ │ ├── 60b9a0d76122ad547f49aa8f4b74f69e.php
│ │ │ ├── 667d5e69b599b3c5375e5ec9ae54104c.php
│ │ │ ├── 67eb75669b7bbda285f33e706c502841.php
│ │ │ ├── 6f82566c99e965c358487569a1e122f1.php
│ │ │ ├── 72ae3554a4182034e696c08fb6c29cc2.php
│ │ │ ├── 744ea0741485a2e4e240abd6beb50dbe.php
│ │ │ ├── 757ed9fa7b0f04c7662016fab6030e62.php
│ │ │ ├── 79253ca8dbb678d82c6c89d59349d2db.php
│ │ │ ├── 7da817c9fbaa34b651fca0a8e166410f.php
│ │ │ ├── 850a09377af9071f39b085f047d905c4.php
│ │ │ ├── 8ddfdc0884988e5052148e930665dbe3.php
│ │ │ ├── 939b340fbcbd520a20ff75e4d8ec4de8.php
│ │ │ ├── 9d8b43744cf82644ad2b1cf802c5021a.php
│ │ │ ├── 9da8cee6e890030e7a493de791c3f04e.php
│ │ │ ├── 9e7864963148f0bff9bcf6abef759b88.php
│ │ │ ├── a4ce5f7ab90571fda576fa4d75248c2b.php
│ │ │ ├── a722b5a487760bea2bdb15afa23760b1.php
│ │ │ ├── a7cc20da4008a8f15c1f175609d93106.php
│ │ │ ├── aaa33f89da7253b027f5d97657f1a75f.php
│ │ │ ├── ad4dddb4014b630297253a61f627f411.php
│ │ │ ├── afb47e02a90d40ec12b606be7cf2116d.php
│ │ │ ├── b676ac7a418d286afc44eea870d96837.php
│ │ │ ├── b909d99d6e1165137cc7b955e63396fb.php
│ │ │ ├── bba07c70d6a80c326b93a941a0d862f7.php
│ │ │ ├── bf4f0ae4ae84fc04b71b5ac17029fc2a.php
│ │ │ ├── c32d87ae5a44282159e0b564fae1aa0d.php
│ │ │ ├── d34d8c86a3256c3c84dee98de94d75bb.php
│ │ │ ├── dc8ca99ba142cce34dd5e93866874acd.php
│ │ │ ├── dc9a158a832b305eadfcfd5d6c7b9a09.php
│ │ │ ├── de57501f861937ab3ebd2ab3a1c1bc34.php
│ │ │ ├── dea54a31f5b0947ce439a5a8db4dc74f.php
│ │ │ ├── df1ea9a6718f87e0f3c851c6e3985aaf.php
│ │ │ ├── df24bb3c9066aa3556946fc82da8ea19.php
│ │ │ ├── e103f12fceabb0378fdc7c7902a3f3ed.php
│ │ │ ├── e52d4f7a5686533f8c02022b156e1356.php
│ │ │ ├── e600ef6f6a73c03cf3815c64a3070f06.php
│ │ │ ├── e7e814ecb8915bd34718ab7cf6b45c51.php
│ │ │ ├── ea75c7f72c7bef8c93183f29133139cd.php
│ │ │ ├── eaa76f78ca078cd21dc5c8ca58108c1b.php
│ │ │ ├── eadc704243fb137c8a697a4f93fc4fbd.php
│ │ │ ├── ed411d103fbe8f1cd5f0272368e7f271.php
│ │ │ ├── ef1f6ce30906dd6cdad728645cb77ff6.php
│ │ │ └── f298e37facf62edd85931cbc172090f0.php
│ │ └── .gitignore
│ └── logs/
│ └── .gitignore
├── tests/
│ ├── Feature/
│ │ ├── Auth/
│ │ │ ├── AuthenticationTest.php
│ │ │ ├── EmailVerificationTest.php
│ │ │ ├── PasswordConfirmationTest.php
│ │ │ ├── PasswordResetTest.php
│ │ │ ├── PasswordUpdateTest.php
│ │ │ └── RegistrationTest.php
│ │ ├── ExampleTest.php
│ │ └── ProfileTest.php
│ ├── Unit/
│ │ └── ExampleTest.php
│ └── TestCase.php
├── .editorconfig
├── .env.example
├── .gitattributes
├── .gitignore
├── README.md
├── artisan
├── composer.json
├── package-lock.json
├── package.json
├── phpunit.xml
├── postcss.config.js
├── tailwind.config.js
└── vite.config.js

────────────────────────────────────────────────────────────────────────────────
Generated by FileTree Pro Extension
