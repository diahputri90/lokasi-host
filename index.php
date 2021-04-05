<!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Peta dan Menyimpan pada Database</title>
        
        <!-- leaflet css  -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
            integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
            crossorigin="" />

        <!-- bootstrap cdn  -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
            integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <style>
            /* ukuran peta */
            #mapid {
                height: 100%;
            }
            .jumbotron{
                height: 100%;
                border-radius: 0;
                background-color: burlywood;
            }
            body{
                background-color: #ebe7e1;
            }
        </style>
    </head>
    
    <body>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-header" style="color: burlywood;">Sistem Informasi Geografis Web dan Mobile</h5>    
                </div>
                <div class="card-body">
                    <div class="row"> <!-- class row digunakan sebelum membuat column  -->
                        <div class="col-4"> <!-- ukuruan layar dengan bootstrap adalah 12 kolom, bagian kiri dibuat sebesar 4 kolom-->
                            <div class="jumbotron"> <!-- untuk membuat semacam container berwarna abu -->
                            <h2>Add Location</h1>
                            <hr>
                                <form action="proses.php" method="post">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Latitude, Longitude</label>
                                        <input type="text" class="form-control" id="latlong" name="latlong">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Nama Tempat</label>
                                        <input type="text" class="form-control" name="nama_tempat">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Kategori Tempat</label>
                                        <select class="form-control" name="kategori" id="">
                                            <option value="">--Kategori Tempat--</option>
                                            <option value="Rumah Makan">Rumah Makan</option>
                                            <option value="Pom Bensin">Pom Bensin</option>
                                            <option value="Fasilitas Umum">Fasilitas Umum</option>
                                            <option value="Pasar/Mall">Pasar/Mall</option>
                                            <option value="Rumah Sakit">Rumah Sakit</option>
                                            <option value="Sekolah">Sekolah</option>
                                            <option value="Universitas">Universitas</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Keterangan</label>
                                        <textarea class="form-control" name="keterangan" cols="30" rows="5"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="col-8"> <!-- ukuruan layar dengan bootstrap adalah 12 kolom, bagian kiri dibuat sebesar 4 kolom-->
                            <!-- peta akan ditampilkan dengan id = mapid -->
                            <div id="mapid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- leaflet js  -->
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
            crossorigin="">
        </script>

        <script>
            // set lokasi latitude dan longitude, lokasinya kota palembang 
            var mymap = L.map('mapid').setView([-8.6972102,115.222177], 19.97);   
            //setting maps menggunakan api mapbox bukan google maps, daftar dan dapatkan token      
            L.tileLayer(
                'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZGlhaHB1dHJpbSIsImEiOiJja2xrbHRxcHkwb2d6MnBvYnJlemlpZWZpIn0.sWcJMwGJz4ImxXYqa2Iahw', {
                    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                    maxZoom: 20,
                    id: 'mapbox/streets-v11', //menggunakan peta model streets-v11 kalian bisa melihat jenis-jenis peta lainnnya di web resmi mapbox
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: 'your.mapbox.access.token'
                }).addTo(mymap);
            
            var marker = L.marker([-8.6972102,115.222177],{
                draggable: "true"
            }).addTo(mymap);
            
                // buat variabel berisi fungsi L.popup
            var popup = L.popup();

            // buat fungsi popup saat map diklik
            function onMapClick(e) {
                popup
                    .setLatLng(e.latlng)
                    .setContent("koordinatnya adalah " + e.latlng
                        .toString()
                        ) //set isi konten yang ingin ditampilkan, kali ini kita akan menampilkan latitude dan longitude
                    .openOn(mymap);

                document.getElementById('latlong').value = e.latlng //value pada form latitude, longitude akan berganti secara otomatis
            }
            mymap.on('click', onMapClick); //jalankan fungsi


            <?php

            $mysqli = mysqli_connect('localhost', 'root', '', 'db_sig'); //koneksi ke database
            $tampil = mysqli_query($mysqli, "select * from lokasi"); //ambil data dari tabel lokasi
            while($hasil = mysqli_fetch_array($tampil)) { ?> //melooping data menggunakan while

            //menggunakan fungsi L.marker(lat, long) untuk menampilkan latitude dan longitude
            //menggunakan fungsi str_replace() untuk menghilankan karakter yang tidak dibutuhkan
            L.marker([<?php echo str_replace(['[', ']', 'LatLng', '(', ')'], '', $hasil['lat_long']); ?>]).addTo(mymap)

                //data ditampilkan di dalam bindPopup (data) dan dapat diskustomisasi dengan html
                .bindPopup(`<?php echo 'Nama Tempat : '.$hasil['nama_tempat'].' |Kategori : '.$hasil['kategori'].' |Keterangan : '.$hasil['keterangan']; ?>`)

            <?php } ?>


        </script>
        
        

    </body>
    
    </html>