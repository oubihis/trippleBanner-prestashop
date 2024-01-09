"use strict";
/**
*  @author    sHKamil - Kamil Hałasa
*  @copyright sHKamil - Kamil Hałasa
*  @license   .l
*/
async function fetchAPI(url, method, data) {
    try {
        const response = await fetch(url, {
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
        let banners = [];
        result.forEach((banner) => {
            banners.push(banner);
        });
        return result;
    }
    catch (error) {
        // Handle errors
        console.error('Error:', error);
    }
}
const setActiveSwitch = (id) => {
    let toActivate = document.getElementById(id.toString());
    toActivate.checked = true;
};
let response = fetchAPI('/module/tripplebanner/TrippleBannerEndpoint?method=getActiveJSON', 'GET');
response.then((banners) => {
    banners.forEach((banner) => {
        setActiveSwitch(banner.id_banner);
    });
});
