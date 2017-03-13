<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Veiculo;
use Illuminate\Pagination\Paginator;

class VeiculoController extends Controller
{

    protected function veiculoValidator($request){
        $validator = Validator::make($request->all(),[
            'marca' => 'required',
            'modelo' => 'required',
            'cor' => 'required',
            'ano' => 'required',
            'preco' => 'required|numeric|min:0',
            ]);
        return $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $veiculo = Veiculo::all();
        return response()->json(['data'=>$veiculo], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->veiculoValidator($request);
        if($validator->fails()){
            return response()->json(['response'=>'Erro', 
                'errors' => $validator->errors()], 
                400);
        }
        $data = $request->only(['marca', 'modelo', 'cor', 'ano', 'preco']);
        if($data){
            $veiculo = Veiculo::create($data);
            if($veiculo){
                return response()->json(['response'=>'Veículo criado com sucesso', 'data'=> $veiculo], 201);
            }else{
                return response()->json(['response'=>'Erro ao criar o veículo', 'data'=> $veiculo], 201);
            }
        }else{
            return response()->json(['response'=>'Dados inválidos'], 400);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id < 0){
            return response()->json(['response'=>'ID menor que zero'], 400);
        }
        $veiculo = Veiculo::find($id);
        if($veiculo){
            return response()->json(['data'=> $veiculo], 200);
        }else{
            return response()->json(['response'=>'O veículo com id '.$id.' não existe'], 404);
        }

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
        $validator = $this->veiculoValidator($request);
        if($validator->fails()){
            return response()->json(['response'=>'Erro', 
                'errors' => $validator->errors()], 
                400);
        }
        $data = $request->only(['marca', 'modelo', 'cor', 'ano', 'preco']);
        if($data){
            $veiculo = Veiculo::find($id);
            if($veiculo){
                $veiculo->update($data);
                return response()->json(['response'=>'Veículo atualizado com sucesso', 'data'=> $veiculo], 200);
            }else{
                return response()->json(['response'=>'O veículo com id '.$id.' não existe'], 400);
            }
        }else{
            return response()->json(['response'=>'Dados inválidos'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id < 0){
            return response()->json(['response'=>'ID menor que zero'], 400);
        }
        $veiculo = Veiculo::find($id);
        if($veiculo){
            $veiculo->delete();
            return response()->json(['response'=>'Veículo removido com sucesso'], 200);
        }else{
            return response()->json(['response'=>'O veículo com id '.$id.' não existe'], 404);
        }
    }

    public function veiculo_page($qtd, $page){
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $veiculos = Veiculo::paginate($qtd);
        return response()->json(['response'=>$veiculos], 200);

    }
    
}
