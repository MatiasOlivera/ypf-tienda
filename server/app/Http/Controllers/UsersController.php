<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UsersRequest\UserCreateRequest;
use App\Http\Requests\UsersRequest\UserUpdateRequest;
use App\User;
use Hash;
use App\Http\Controllers\BaseController;
use App\Auxiliares\Consulta;
use App\Auxiliares\Paginacion;
use App\Auxiliares\Mensaje;


class UsersController extends Controller
{

    /**
     * Muestra una lista de usuarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $campos     = ['id', 'name', 'email', 'email_verified_at', 'id_cliente', 'created_at', 'updated_at', 'deleted_at', ];
        $relaciones = null;
        $buscar     = $request->input("buscar", null);
        $eliminados = $request->input("eliminados", false);
        $paginado   = $request->input("paginado", 10);
        $campoOrden = $request->input("campoOrden", 'name');
        $orden      = $request->input("orden", true);

        //paginado
        $paginacion = new Paginacion;
        $paginacion->setRegistrosPorPagina($paginado);
        $paginacion->setCampoOrden($campoOrden);
        ($orden === true || $orden == 'true') ? $paginacion->setOrdenASC() : $paginacion->setOrdenDESC();

        //consulta
        $consulta = new Consulta;
        if ($eliminados === true || $eliminados == 'true') {
            $consulta->soloEliminados();
        };
        $consulta->setBuscar($buscar);
        $consulta->setModelosRelacionados($relaciones);
        $consulta->setCampos($campos);

        //mensajes
        $mensajes = new Mensaje;
        $mensajes->setMensajeError("Tubimos un problema, vuelva a intentarlo", 'CATCH_USER_INDEX');

        $metodo = new BaseController();
        $modelo = new User();

        return $metodo->index($consulta, $modelo, $paginacion, $mensajes);
    }

    /**
     * Guarda un nuevo usuario en la BD.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $mensaje = new Mensaje;
        $metodo   = new BaseController();
        $modelo   = new User();
        $inputs   = $request->all();
        $nombre   = $request->input('name');
        $inputs['password'] = Hash::make($request->input('password'));
        unset($inputs['password_confirmation']);

        //mensajes
        $mensaje->setMensajeExito("Usuario {$nombre} Creado con exito");
        $mensaje->setMensajeError("No pudimos guardar el Usuario {$nombre}", 'CATCH_USER_STORE');

        return $metodo->store($inputs, $modelo, $mensaje);
    }

    /**
     * Muestra un usuario especifico.
     *
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Actualizar el usuario especifico en la BD.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Request  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $inputs  = $request->all();
        $nombre  = $request->input('name');
        $metodo  = new BaseController();
        $mensaje = new Mensaje;

        $mensaje->setMensajeExito("El usuario {$nombre} ha sido actualizado");
        $mensaje->setMensajeError("Hubo un problema al intentar actualizar el Usuario  {$nombre}", 'CATCH_USER_UPDATE');

        return $metodo->update($inputs, $user, $mensaje);
    }

    /**
     * elimina el usuario especifico de la BD
     *
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $nombre  = $user->name;
        $metodo  = new BaseController();
        $mensaje = new Mensaje;

        $mensaje->setMensajeExito("se elimino a {$nombre} con exito");
        $mensaje->setMensajeError("Hubo un problema al intentar eliminar a {$nombre}", 'CATCH_USER_DESTROY');

        return $metodo->destroy($user, $mensaje);
    }

    /**
     * Restaurar el usuario que ha sido eliminado
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function restore(User $user)
    {
        $nombre  = $user->name;
        $metodo  = new BaseController();
        $mensaje = new Mensaje;

        $mensaje->setMensajeExito("{$nombre} ha sido dada de alta");
        $mensaje->setMensajeError("Hubo un problema al intentar dar de alta a {$nombre}", 'CATCH_USER_RESTORE');

        return $metodo->restore($user, $mensaje);
    }
}
