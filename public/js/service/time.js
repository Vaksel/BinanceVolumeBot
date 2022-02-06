function formatDate(date)
{
    let dateOfyear = date.getFullYear() + ""; // год;
    // console.log(dateOfyear);
    // let newDateOfyear = dateOfyear.slice(2); // год последние две цифры;

    let day = date.getDate(); // текущий день
    day = day < 10 ? "0" + day : day;
    let month = date.getMonth() + 1; //текущий месяцж
    month = month < 10 ? "0" + month : month;

    return +dateOfyear + "." + month + "." + day;
}

function formatDateDB(date)
{
    let dateOfyear = date.getFullYear() + ""; // год;
    // console.log(dateOfyear);
    // let newDateOfyear = dateOfyear.slice(2); // год последние две цифры;

    let day = date.getDate(); // текущий день
    day = day < 10 ? "0" + day : day;
    let month = date.getMonth() + 1; //текущий месяцж
    month = month < 10 ? "0" + month : month;

    return dateOfyear + "-" + month + "-" + day;
}

function getDateTime(timestamp, isDB)
{
    let currentDateTime = new Date(timestamp);

    let hours = currentDateTime.getHours() < 10 ? '0' + currentDateTime.getHours() : currentDateTime.getHours();

    let minutes = currentDateTime.getMinutes() < 10 ? '0' + currentDateTime.getMinutes() : currentDateTime.getMinutes();

    let seconds = currentDateTime.getSeconds() < 10 ? '0' + currentDateTime.getSeconds() : currentDateTime.getSeconds();

    let dateTime;

    if(isDB)
    {
        dateTime = formatDateDB(currentDateTime) + ' ' + hours + ':' +
            minutes + ':' + seconds;
    }
    else
    {
        dateTime = formatDate(currentDateTime) + ' ' + hours + ':' +
            minutes + ':' + seconds;
    }

    return dateTime;
}
