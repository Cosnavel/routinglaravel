<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
    return view('create_certificate');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
    return "Das ist das Zertifikat mit der Zertifikatsnummer: " . $id;
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  public function nameList()
  {
    $names = ['Andrea', 'Ben', 'Charlie', 'Kyle', 'Bob', 'Ryan', 'Nick', 'Moritz', 'Lisa', 'Amanda', 'Chris'];
    return view('show_names', compact('names'));
  }

  public function showUser()
  {
    $users = [
      [
        'name' => 'Cathy Gleichner',
        'email' => 'Cristobal_Volkman89@hotmail.com',
        'phone' => '1-102-339-0647 x06086',
        'age' => '14'
      ],
      [
        'name' => 'Rashad Bartoletti',
        'email' => 'Josephine70@gmail.com',
        'age' => '23'
      ],
      [
        'name' => 'Anabel Crooks',
        'email' => 'Lambert.Braun38@hotmail.com',
        'phone' => '1-455-074-9861 x97241',
        'age' => '56'
      ],
      [
        'name' => 'Ova Howe',
        'email' => 'Diego_Turner@yahoo.com',
        'age' => '4'
      ],
      [
        'name' => 'Loy Balistreri',
        'email' => 'Emily.Senger68@hotmail.com',
        'age' => '87'
      ],
      [
        'name' => 'Tamia Parisian',
        'email' => 'Arlie77@gmail.com',
        'phone' => '633.048.2602',
        'age' => '13'
      ],
      [
        'name' => 'Demario Boehm',
        'email' => 'Annie.MacGyver@yahoo.com',
        'phone' => '258.282.8669 x9776',
        'age' => '35'
      ],
      [
        'name' => 'Tianna Jacobi I',
        'email' => 'Elliot32@hotmail.com',
        'age' => '43'
      ],
      [
        'name' => 'Rosemary Heidenreich',
        'email' => 'Cornelius.King23@hotmail.com',
        'phone' => '638-129-2815 x184',
        'age' => '54'
      ],
      [
        'name' => 'Jonas Gaylord',
        'email' => 'Wilburn14@yahoo.com',
        'phone' => '(348) 253-3467 x129',
        'age' => '49'
      ],
      [
        'name' => 'Juanita Luettgen PhD',
        'email' => 'Kaelyn_Predovic@hotmail.com',
        'phone' => '(229) 085-6914',
        'age' => '27'
      ],
      [
        'name' => 'Ms. Dedrick Quigley',
        'email' => 'Brandi.Glover@gmail.com',
        'phone' => '184.536.7463 x687',
        'age' => '32'
      ],
      [
        'name' => 'Britney Upton',
        'email' => 'Lela_Labadie@hotmail.com',
        'phone' => '1-684-898-5084 x7777',
        'age' => '61'
      ],
      [
        'name' => 'Alysha Stamm',
        'email' => 'Jacinto.Langosh@gmail.com',
        'phone' => '559.743.3564',
        'age' => '36'
      ],
      [
        'name' => 'Jonas Cummings',
        'email' => 'Stephania.Ebert65@gmail.com',
        'phone' => '254-084-9491 x8652',
        'age' => '19'
      ],
      [
        'name' => 'Esther Tromp',
        'email' => 'Baylee22@yahoo.com',
        'phone' => '1-039-607-9412 x0385',
        'age' => '72'
      ],
      [
        'name' => 'Wilhelm Ullrich',
        'email' => 'Norene.Hartmann@hotmail.com',
        'phone' => '(559) 229-9496',
        'age' => '93'
      ]
    ];
    return view('show_user', compact('users'));
  }
}
