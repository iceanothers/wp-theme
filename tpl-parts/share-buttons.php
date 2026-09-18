<?php
$share_title = htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
$share_url   = rawurlencode( get_permalink() );
?>

<li><a class="i_fcbk" href="https://www.facebook.com/sharer.php?u=<?php echo esc_attr( $share_url ); ?>&amp;quote=<?php echo esc_html( $share_title ); ?>"
       title="Share at Facebook" target="_blank" rel="noopener"></a></li>
<li><a class="i_twtr" href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $share_url ); ?>&amp;text=<?php echo esc_html( $share_title ); ?>"
       title="Tweet It" target="_blank" rel="noopener"></a></li>
<li><a class="i_lnkdn" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr( $share_url ); ?>"
       title="Share at LinkedIn" target="_blank" rel="noopener"></a></li>
<li><a class="i_whtsp" href="https://api.whatsapp.com/send?text=<?php echo esc_attr( $share_url ); ?>"
       data-action="share/whatsapp/share" target="_blank" rel="noopener" title="Share at WhatsApp"></a></li>
<li><a class="i_envelope_o" href="mailto:?subject=<?php echo esc_html( $share_title ); ?>&amp;body=<?php echo esc_attr( $share_url ); ?>"
       title="Send via email"></a></li>
