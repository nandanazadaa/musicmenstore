<footer id="contact" class="footer-section w-full">
    <div class="footer-contacts-title">
        <h2>{{ $contactTitle ?? 'CONTACTS' }}</h2>
    </div>

    <div class="footer-content">
        <div class="footer-logo-section">
            <div class="footer-logo">
                <img src="{{ asset($contactLogoPath ?? 'images/logo3.png') }}" 
                     alt="Musicmen Store - Logo footer"
                     loading="lazy"
                     width="200"
                     height="auto">
            </div>
        </div>

        <div class="footer-contact-info">
            <div class="footer-contact-item">
                <div class="footer-contact-icon" aria-hidden="true">
                    @if ($addressIconPath)
                        <img src="{{ asset($addressIconPath) }}"
                            class="h-6 w-6 object-contain filter brightness-0 invert"
                            alt=""
                            loading="lazy"
                            width="24"
                            height="24">
                    @endif
                </div>
                <div class="footer-contact-text">{{ $addressText }}</div>
            </div>

            <div class="footer-contact-item">
                <div class="footer-contact-icon" aria-hidden="true">
                    @if ($phoneIconPath)
                        <img src="{{ asset($phoneIconPath) }}"
                            class="h-6 w-6 object-contain filter brightness-0 invert"
                            alt=""
                            loading="lazy"
                            width="24"
                            height="24">
                    @endif
                </div>
                <div class="footer-contact-text">{{ $phoneText }}</div>
            </div>

            <div class="footer-contact-item" style="flex-direction: column; align-items: flex-start; gap: 10px;">
                <div class="footer-social-label">Social Media</div>
                <div class="footer-social-icons">
                    @php
                        $socialMediaJson = \App\Models\LandingPageSetting::getValue('contact', 'social_media', '[]');
                        $socialMedia = json_decode($socialMediaJson, true) ?: [];

                        // Backward compatibility with old single social media
                        if (empty($socialMedia)) {
                            $oldIcon = \App\Models\LandingPageSetting::getValue('contact', 'social_icon', null);
                            $oldLink = \App\Models\LandingPageSetting::getValue('contact', 'social_link', '#');
                            if ($oldIcon || $oldLink) {
                                $socialMedia = [
                                    [
                                        'icon' => $oldIcon,
                                        'link' => $oldLink,
                                    ],
                                ];
                            }
                        }
                    @endphp
                    @if (!empty($socialMedia))
                        @foreach ($socialMedia as $index => $social)
                            @if (isset($social['link']) && !empty($social['link']))
                                <a href="{{ $social['link'] }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="footer-social-icon"
                                   aria-label="Kunjungi kami di media sosial {{ $index + 1 }}">
                                    @if (isset($social['icon']) && !empty($social['icon']))
                                        <img src="{{ asset($social['icon']) }}"
                                            class="h-6 w-6 object-contain filter brightness-0 invert"
                                            alt="Ikon media sosial"
                                            loading="lazy"
                                            width="24"
                                            height="24">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                            viewBox="0 0 24 24" aria-hidden="true">
                                            <path
                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                        </svg>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-map-section">
            <div class="footer-map" style="width: 100%; height: 300px; overflow: hidden; border-radius: 8px;">
                @if (!empty($mapsLink))
                    <iframe src="{{ $mapsLink }}" width="100%" height="100%" style="border:0;" allowfullscreen=""
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Musicmen Store Location">
                    </iframe>
                @else
                    <div
                        class="flex items-center justify-center h-full bg-[#1a1a1a] text-gray-500 border border-[#4a4a4a] rounded-lg">
                        <p>Google Maps belum dikonfigurasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="footer-separator"></div>

    <div class="footer-copyright">
        Copyright © {{ date('Y') }} • By Musicmen.
    </div>
</footer>
