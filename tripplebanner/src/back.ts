/**
 *  @author    sHKamil - Kamil Hałasa
 *  @copyright sHKamil - Kamil Hałasa
 *   @license   GPL
 */

type banner = {
    id_banner: number,
    image_path: string,
    link: string,
    active: number
}

async function fetchAPI(url: string, method: string, data?: any) {   
    try {
        const response = await fetch(
            url,
            {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: data ? JSON.stringify(data) : undefined,
            });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const result = await response.json();
        let banners: banner[] = [];
        result.forEach((banner: banner) => {
            banners.push(banner);
        })
        
        return result;

    } catch (error) {
        // Handle errors
        console.error('Error:', error);
    }
}

const setActiveSwitch = (id: number) => {
    let toActivate = <HTMLInputElement> document.getElementById(id.toString());
    toActivate.checked = true;
}

let response = fetchAPI('/module/tripplebanner/TrippleBannerEndpoint?method=getActiveJSON', 'GET');

response.then((banners) => {
    banners.forEach((banner: banner) => {
        setActiveSwitch(banner.id_banner);
    })
});
