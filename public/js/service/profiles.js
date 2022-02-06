function getCurrentProfileId()
{
    let profileId = getCookie('chosenProfile')

    if(profileId !== undefined && profileId !== null && profileId !== '')
    {
        return profileId;
    }

    return 0;
}