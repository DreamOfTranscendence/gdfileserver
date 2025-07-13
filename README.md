# gdfileserver
Google drive file server (a static-content http server backend to fetch public files stored on google drive, to replace googles own discontinued static web hosting for drive).

f.php is the main code file.
gdkey.json is where you put your google developer google drive api key

stor_01.json is a cache of directory content LISTS fetched from google drive and stored to speed up load/fetch time next time. No actual files are stored, only the directory indexes and google drive file IDs.

the $fid variable near the beginning of f.php is folder_ID, googledrive folder id of the "root" directory you want to serve public static files from.
I still need to add the "just put root folder id in request url" functionality, anyone who knows php can add it though.

This "root" directory is arbitrary, it does NOT have to be the root folder of your google drive, just the root of whatever googledrive files you want staticly web-hosted.
