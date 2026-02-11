@props(['url', 'logo' => null])

<tr>
    <td class="header" style="padding: 25px 0; text-align: center;">
        <a href="{{ $url }}" style="display:inline-block; text-decoration:none;">
            @if($logo)
                <img src="{{ $logo }}"
                     alt="Talibon Polytechnic College Logo"
                     style="height:70px; width:auto; display:block; margin:0 auto 10px;">
            @endif

            <div style="color:#008000; font-size:20px; font-weight:700; line-height:1.2;">
                {{ $slot }}
            </div>
        </a>
    </td>
</tr>
