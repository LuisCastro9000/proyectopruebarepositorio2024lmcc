<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosController;
use DB;
use Session;

class Rubros extends Controller
{
    public function insertarDatosRubros($codigoCliente, $rubro, $sucursal) {
        if($rubro == 1){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['Sony', 'Asus', 'HP', 'Samsung', 'Lenovo'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['PCs', 'Laptops', 'Tablets', 'SmartPhones', 'Accesorios'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[4]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'SONY XPERIA XA2', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            $this->insertarStock($sucursal);
            
            $array2= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'LAPTOP IDEAPAD 330 15.6" INTEL CORE I7 1TB 8GB', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            $this->insertarStock($sucursal);
            
            $array3= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'TABLET CALAXY E 9.6" 8GB 1.5GB', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            $this->insertarStock($sucursal);
            
            $array4= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[4]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'LENOVO ALL IN ONE IDEACENTRE AIO 520 23.8" AMD A9 1TB 4GB', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            $this->insertarStock($sucursal);
            
            $array5= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'LAPTOP 15-DA0028LA 15.6" CORE I5 1TB 8GB', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            $this->insertarStock($sucursal);
            
            $array6= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'TECLADO OMEN CON STEELSERIES', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            $this->insertarStock($sucursal);
            
            $array7= ['IdMarca' => $marcas[3]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'LAPTOP GL703GE-GC093T 17.3" CORE i7 1TB 12GB 4GB', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            $this->insertarStock($sucursal);
            
            $array8= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'MOUSE OMEN CON STEELSERIES', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            $this->insertarStock($sucursal);
            
            $array9= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Soporte Técnico y Mantenimiento de Equipos', 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
            
            $array10= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Envio de Producto', 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array10);
           
        }
        if($rubro == 2){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['Pepsi', 'Costeño', 'Bimbo', 'Otto Kunz', 'La Florencia'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['Bebidas', 'Abarrotes', 'Verduras', 'Panedería y Pastelería', 'Embutidos'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[4]->IdMarca, 'IdCategoria' => $categorias[4]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Gaseosa PEPSI Botella 2.25L', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            $this->insertarStock($sucursal);
            
            $array2= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Jamón Inglés OTTO KUNZ', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            $this->insertarStock($sucursal);
            
            $array3= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Tocino Ahumado Especial OTTO KUNZ', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            $this->insertarStock($sucursal);
            
            $array4= ['IdMarca' => $marcas[3]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Garbanzo COSTEÑO Bolsa 500g', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            $this->insertarStock($sucursal);
            
            $array5= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Tostadas BIMBO Integrales Paquete 10un', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            $this->insertarStock($sucursal);
            
            $array6= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Keke BIMBO Marmoleado Bolsa 380g', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            $this->insertarStock($sucursal);
            
            $array7= ['IdMarca' => $marcas[3]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Arroz Extra COSTEÑO Bolsa 750g', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            $this->insertarStock($sucursal);
            
            $array8= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Rabanito LA FLORENCIA Bolsa 400g', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            $this->insertarStock($sucursal);
            
            $array9= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Delivery', 'Precio' => '5.00', 'Exonerado' => 1, 'Costo' => '3.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
            
            $array10= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Pedidos y Recojo', 'Precio' => '5.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array10);
        }
        if($rubro == 3){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['Otros'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['Jugos', 'Entradas', 'Principales', 'Postres', 'Té e Infusiones'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[4]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Jugo de Papaya', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            $this->insertarStock($sucursal);
            
            $array2= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Arroz con Pollo', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            $this->insertarStock($sucursal);
            
            $array3= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ají de Gallina', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            $this->insertarStock($sucursal);
            
            $array4= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ceviche', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            $this->insertarStock($sucursal);
            
            $array5= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ensalada Rusa', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            $this->insertarStock($sucursal);
            
            $array6= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Manzanilla', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            $this->insertarStock($sucursal);
            
            $array7= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Mazamorra', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            $this->insertarStock($sucursal);
            
            $array8= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Crema Volteada', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            $this->insertarStock($sucursal);
            
            $array9= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Café', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
            $this->insertarStock($sucursal);
            
            $array10= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Delivery', 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array10);
        }
        if($rubro == 4){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['Johnson', 'Huggies', 'Tapsin', 'Ensure', 'Pantene'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['Jabones', 'Pañales', 'Leches', 'Shampoos', 'Medicinas'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[3]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Pañal Huggies Unisex Talla M Active Sec', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            $this->insertarStock($sucursal);
            
            $array2= ['IdMarca' => $marcas[3]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Pañal Huggies Calzoncito Niño Talla XG Natural Care', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            $this->insertarStock($sucursal);
            
            $array3= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Tapsin Instaflu Día Comprimido', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            $this->insertarStock($sucursal);
            
            $array4= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Tapsin Instaflu Noche Comprimido', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            $this->insertarStock($sucursal);
            
            $array5= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ensure Advance Sabor Vainilla', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            $this->insertarStock($sucursal);
            
            $array6= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ensure Advance Líquido Sabor Vainilla', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            $this->insertarStock($sucursal);
            
            $array7= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Shampoo Fuerza y Reconstrucción Pantene Pro-V', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            $this->insertarStock($sucursal);
            
            $array8= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Shampoo Rizos Definidos 2en1 Pantene Pro-V', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            $this->insertarStock($sucursal);
            
            $array9= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Delivery', 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
        }
        if($rubro == 5){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['Prodac', 'Ducasse', 'Philips', 'Stanley', 'Bosch'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['Tornillos', 'Clavos', 'Herramientas Eléctricas', 'Accesorios', 'Herramientas Manuales'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[4]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 2, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Clavo albañil con cabeza 3" 30 kg', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            $this->insertarStock($sucursal);
            
            $array2= ['IdMarca' => $marcas[3]->IdMarca, 'IdCategoria' => $categorias[4]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 2, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Tornillo Aglomerado 3.5" x 50mm x 500 Unidades', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            $this->insertarStock($sucursal);
            
            $array3= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Starter Kit con 2 Baterías', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            $this->insertarStock($sucursal);
            
            $array4= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Tronzadora 2300 W D28730-B2', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            $this->insertarStock($sucursal);
            
            $array5= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Martillo de Bola 32 oz', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            
            $array6= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Arrancador S-2 Azul 4 - 22 W', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            $this->insertarStock($sucursal);
            
            $array7= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Llave de Tubo Stillson 18"', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            $this->insertarStock($sucursal);
            
            $array8= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Disco Diamantado 7" para Concreto', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            $this->insertarStock($sucursal);
            
            $array9= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Instalaciones y Reparaciones', 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
            
            $array10= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Pedidos y Recojo', 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array10);
        }
        if($rubro == 6){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['SERVICENTRO RAMIREZ S.A.C.', 'ENERGIGAS S.A.C.', 'GRANEL INDUSTRIAL S.A.C.', 'ESTACION MIRAFLORES S.A.C.', 'SEMAR S.A.C.'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['Diesel Gasolina', 'GNV', 'GLP'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[4]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 5, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Gasohol 97 Plus', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            $this->insertarStock($sucursal);
            
            $array2= ['IdMarca' => $marcas[3]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 5, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Gasohol 95 Plus', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            $this->insertarStock($sucursal);
            
            $array3= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 6, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Gas Natural Vehicular', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            $this->insertarStock($sucursal);
            
            $array4= ['IdMarca' => $marcas[3]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 6, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Gas Natural Vehicular', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            $this->insertarStock($sucursal);
            
            $array5= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 5, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Gasohol 95 Plus', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            $this->insertarStock($sucursal);
            
            $array6= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 7, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'GLP-Granel', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            $this->insertarStock($sucursal);
            
            $array7= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[0]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 7, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'GLP-Granel', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            $this->insertarStock($sucursal);
            
            $array8= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 5, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Gasohol 90 Plus', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            $this->insertarStock($sucursal);
            
            $array9= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Delivery', 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
            
        }
        if($rubro == 7){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['Primavera Hotel', 'Hotel El Mirador', 'Paraiso Hotel', 'Jorge Chávez'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['Especial', 'Por Noche', 'Por Días', 'Por Horas'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 8, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Habitación Familiar', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            $this->insertarStock($sucursal);
            
            $array2= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 9, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Habitación Doble', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            $this->insertarStock($sucursal);
            
            $array3= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 8, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Habitación Triple', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            $this->insertarStock($sucursal);
            
            $array4= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[3]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 8, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Suite', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            $this->insertarStock($sucursal);
            
            $array5= ['IdMarca' => $marcas[1]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 9, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Habitación Doble', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            $this->insertarStock($sucursal);
            
            $array6= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 9, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Suite Familiar', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            $this->insertarStock($sucursal);
            
            $array7= ['IdMarca' => $marcas[2]->IdMarca, 'IdCategoria' => $categorias[2]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 9, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Habitación Triple', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            $this->insertarStock($sucursal);
            
            $array8= ['IdMarca' => $marcas[3]->IdMarca, 'IdCategoria' => $categorias[1]->IdCategoria, 'IdTipo' => 1, 'IdUnidadMedida' => 8, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Habitación Doble', 'Stock' => 0, 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            $this->insertarStock($sucursal);
            
            $array9= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Servicio Especial', 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
            
            $array10= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Servicio Premium', 'Precio' => '0.00', 'Exonerado' => 1, 'Costo' => '0.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array10);
        }
        /*if($rubro == 8){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['Prodac', 'Ducasse', 'Philips', 'Stanley', 'Bosch'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['Jugos', 'Entradas', 'Principales', 'Postres', 'Té e Infusiones'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[4], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Jugo de Papaya', 'Stock' => 10, 'Precio' => '3.00', 'Exonerado' => 1, 'Costo' => '1.50', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            
            $array2= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Arroz con Pollo', 'Stock' => 10, 'Precio' => '8.00', 'Exonerado' => 1, 'Costo' => '4.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            
            $array3= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ají de Gallina', 'Stock' => 10, 'Precio' => '8.00', 'Exonerado' => 1, 'Costo' => '4.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            
            $array4= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[3], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ceviche', 'Stock' => 10, 'Precio' => '4.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            
            $array5= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[3], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ensalada Rusa', 'Stock' => 10, 'Precio' => '4.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            
            $array6= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[0], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Manzanilla', 'Stock' => 10, 'Precio' => '2.00', 'Exonerado' => 1, 'Costo' => '1.20', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            
            $array7= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Mazamorra', 'Stock' => 10, 'Precio' => '2.50', 'Exonerado' => 1, 'Costo' => '1.50', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            
            $array8= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Crema Volteada', 'Stock' => 10, 'Precio' => '2.50', 'Exonerado' => 1, 'Costo' => '1.50', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            
            $array9= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[0], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Café', 'Stock' => 10, 'Precio' => '2.00', 'Exonerado' => 1, 'Costo' => '1.20', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
            
            $array10= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Delivery', 'Stock' => 10, 'Precio' => '5.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array10);
        }
        if($rubro == 9){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['Prodac', 'Ducasse', 'Philips', 'Stanley', 'Bosch'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['Jugos', 'Entradas', 'Principales', 'Postres', 'Té e Infusiones'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[4], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Jugo de Papaya', 'Stock' => 10, 'Precio' => '3.00', 'Exonerado' => 1, 'Costo' => '1.50', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            
            $array2= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Arroz con Pollo', 'Stock' => 10, 'Precio' => '8.00', 'Exonerado' => 1, 'Costo' => '4.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            
            $array3= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ají de Gallina', 'Stock' => 10, 'Precio' => '8.00', 'Exonerado' => 1, 'Costo' => '4.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            
            $array4= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[3], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ceviche', 'Stock' => 10, 'Precio' => '4.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            
            $array5= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[3], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ensalada Rusa', 'Stock' => 10, 'Precio' => '4.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            
            $array6= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[0], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Manzanilla', 'Stock' => 10, 'Precio' => '2.00', 'Exonerado' => 1, 'Costo' => '1.20', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            
            $array7= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Mazamorra', 'Stock' => 10, 'Precio' => '2.50', 'Exonerado' => 1, 'Costo' => '1.50', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            
            $array8= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Crema Volteada', 'Stock' => 10, 'Precio' => '2.50', 'Exonerado' => 1, 'Costo' => '1.50', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            
            $array9= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[0], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Café', 'Stock' => 10, 'Precio' => '2.00', 'Exonerado' => 1, 'Costo' => '1.20', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
            
            $array10= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Delivery', 'Stock' => 10, 'Precio' => '5.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array10);
        }
        if($rubro == 10){
            $idUsuario = Session::get('idUsuario');
            $loadDatos = new DatosController();
            
            $arrayMarca = ['Prodac', 'Ducasse', 'Philips', 'Stanley', 'Bosch'];
            $this->insertarTablaMarca($arrayMarca, $sucursal);

            $arrayCategoria = ['Jugos', 'Entradas', 'Principales', 'Postres', 'Té e Infusiones'];
            $this->insertarTablaCategoria($arrayCategoria, $sucursal);
 
            $categorias = $loadDatos->getCategorias($codigoCliente);
            $marcas = $loadDatos->getMarcas($codigoCliente);
            $fecha = $loadDatos->getDateTime();
            
            $array1= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[4], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Jugo de Papaya', 'Stock' => 10, 'Precio' => '3.00', 'Exonerado' => 1, 'Costo' => '1.50', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array1);
            
            $array2= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Arroz con Pollo', 'Stock' => 10, 'Precio' => '8.00', 'Exonerado' => 1, 'Costo' => '4.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array2);
            
            $array3= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[2], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ají de Gallina', 'Stock' => 10, 'Precio' => '8.00', 'Exonerado' => 1, 'Costo' => '4.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array3);
            
            $array4= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[3], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ceviche', 'Stock' => 10, 'Precio' => '4.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array4);
            
            $array5= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[3], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Ensalada Rusa', 'Stock' => 10, 'Precio' => '4.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array5);
            
            $array6= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[0], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Manzanilla', 'Stock' => 10, 'Precio' => '2.00', 'Exonerado' => 1, 'Costo' => '1.20', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array6);
            
            $array7= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Mazamorra', 'Stock' => 10, 'Precio' => '2.50', 'Exonerado' => 1, 'Costo' => '1.50', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array7);
            
            $array8= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[1], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Crema Volteada', 'Stock' => 10, 'Precio' => '2.50', 'Exonerado' => 1, 'Costo' => '1.50', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array8);
            
            $array9= ['IdMarca' => $marcas[0]->IdMarca, 'IdCategoria' => $categorias[0], 'IdTipo' => 1, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Café', 'Stock' => 10, 'Precio' => '2.00', 'Exonerado' => 1, 'Costo' => '1.20', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array9);
            
            $array10= ['IdTipo' => 2, 'IdUnidadMedida' => 1, 'IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario,
                'Descripcion' => 'Delivery', 'Stock' => 10, 'Precio' => '5.00', 'Exonerado' => 1, 'Costo' => '2.00', 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            $this->insertarTablaProducto($array10);
        }*/
    }
    
    private function insertarTablaCategoria($arrayCategoria, $sucursal) {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $fecha = $loadDatos->getDateTime();
        for($i=0 ; $i<count($arrayCategoria); $i++){
            $array = ['IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Nombre' => $arrayCategoria[$i], 'Descripcion' => $arrayCategoria[$i], 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            DB::table('categoria')->insert($array);
        }
        
    }
    
    private function insertarTablaMarca($arrayMarca, $sucursal) {
        $loadDatos = new DatosController();
        $idUsuario = Session::get('idUsuario');
        $fecha = $loadDatos->getDateTime();
        for($i=0 ; $i<count($arrayMarca); $i++){
            $array = ['IdSucursal' => $sucursal, 'FechaCreacion' => $fecha, 'IdCreacion' => $idUsuario, 'Nombre' => $arrayMarca[$i], 'Descripcion' => $arrayMarca[$i], 'Imagen' => 'https://s3-us-west-2.amazonaws.com/2019mifacturita/not-found.jpg', 'Estado' => 'E'];
            DB::table('marca')->insert($array);
        }
    }
    
    private function insertarTablaProducto($array) {
        DB::table('articulo')->insert($array);
    }
    
    private function insertarStock($sucursal) {
        $loadDatos = new DatosController();
        $producto = $loadDatos->getProductoUltimoStock($sucursal);
        $_array = ['IdArticulo' => $producto->IdArticulo, 'Costo' => '0.00', 'Precio' => '0.00', 'Cantidad' => 0];
        DB::table('stock')->insert($_array);
    }
}
