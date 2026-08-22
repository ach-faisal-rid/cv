<?php 

    // variable adalah tempat untuk menyimpan data
    $title = "Faisal Achmad Ridhani — CV";
    
    // pemanggilan variable adalah dengan menampilkan nilainya
    echo $title;

    // kita kemarin sudah bikin yang di atas 
    
    echo "<br>";
    // var_dump() untuk menampilkan tipe data dan nilainya
    var_dump(5);
    var_dump('nilai 2');
    var_dump(true);

    // bagaimana kalau kita punya nilai seperti ini

    $x = $y = $z = "buah-buahan";
    
    // mengambil nilai dari variabel sebelumnya

    // Mengambil nilai dari $x untuk variabel baru

    // Output: buah-buahan
    // Mengambil Nilai Secara Individual
    $stok_pasar = $x;
    echo "<br>";
    echo $stok_pasar; 
    // Output: buah-buahan

    // Mengambil Nilai Bersamaan (Destructuring Array)
    $buah = ['apel', 'mangga', 'pisang'];
    list($a, $b, $c) = $buah;
    echo "<br>";   
    echo $a; // Output: apel
    echo "<br>";
    echo $b; // Output: mangga
    echo "<br>";
    echo $c; // Output: pisang
    echo "<br>";

    //Mengambil Nilai Bersamaan (Destructuring Array)
    $buah = ['apel', 'mangga', 'pisang'];
    [$buah1, $buah2, $buah3] = $buah;
    echo "<br>";
    echo $buah[0]; // Output: apel
    echo "<br>";
    echo $buah1; // Output: apel
    echo "<br>";
    echo $buah2; // Output: mangga
    echo "<br>";
    echo $buah3; // Output: pisang
    echo "<br>";
    print_r($buah);

    // array multidimensi
    $buah = [
        'apel' => [
            'harga' => 10000,
            'stok' => 10
        ],
        'mangga' => [
            'harga' => 15000,
            'stok' => 15
        ],
        'pisang' => [
            'harga' => 20000,
            'stok' => 20
        ]
    ];
    echo "<br>";
    print_r($buah);
    echo "<br>";
    echo $buah['apel']['harga']; // Output: 10000
    echo "<br>";
    echo $buah['mangga']['harga']; // Output: 15000
    echo "<br>";
    echo $buah['pisang']['harga']; // Output: 20000
?>

