@if( isset($social) && !empty($social))
    <table cellpadding="0" cellspacing="0" border="0" width="100%"
           class="social-media-container">
        <tr>
            @for ($i=0; $i < count($social); $i++)
                <td align="center" width="{{ floor(100/count($social))  }}%">
                    <a href="{{ $social[$i]['link_url']  }}"><img src="{{ $social[$i]['link_image_64'] }}"
                                                                  id="social-{{ $social[$i]['link_slug'] }}"
                                                                  class="social-media-icon"
                                                                  alt="{{ $social[$i]['link_name']  }}"></a>
                </td>
            @endfor
        </tr>
    </table>
@endif