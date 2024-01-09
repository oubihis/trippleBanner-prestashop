{if Configuration::get('TRIPPLEBANNER_LIVE_MODE')}
    {if !empty($banners)}
        {if count($banners) > 1 && count($banners) < 4}
            <div id="trippleBanner">
                {foreach $banners as $banner}
                    <a class="banner-link" href="{$banner.link}">
                        <img class="banner-img" src="{$banner.image_path}">
                    </a>
                {/foreach}
            </div>
        {elseif count($banners) == 1}
            <div id="trippleBanner" style="justify-content: center;">
                <a class="banner-link" style="width: 80%; max-height: 20rem;"href="{$banners[0].link}">
                    <img class="banner-img" style="    
                    width: 100%;
                    object-fit: cover;" src="{$banners[0].image_path}"
                    >
                </a>
            </div>
        {elseif count($banners) > 3}
            <div id="trippleBanner">
                {for $i=0 to 2}
                    <a class="banner-link" href="{$banners[$i].link}">
                        <img class="banner-img" src="{$banners[$i].image_path}">
                    </a>
                {/for}
            </div>
        {/if}
    {/if}
{/if}
