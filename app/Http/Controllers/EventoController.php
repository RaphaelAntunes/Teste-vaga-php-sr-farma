<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventoModel;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;
use OpenApi\Annotations\Info; // Certifique-se de importar a classe Info


class EventoController extends Controller
{
    /**
        * @OA\Post(
        * path="/criar-evento",
        * operationId="event",
        * tags={"Eventos"},
        * summary="Create event",
        * security={ {"bearer": {} }},
        * description="responsável por criar um evento",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"title", "description", "status", "start", "end"},
        *               @OA\Property(property="title", type="string"),
        *               @OA\Property(property="description", type="string"),
        *               @OA\Property(property="start", type="timestamp"),
        *               @OA\Property(property="end", type="timestamp")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=204,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function criarEvento(Request $request)
{
    $usuarioEmail = Auth::user()->email;
    $dataInicio = $request->input('start'); // Data de início do novo evento
    echo($usuarioEmail);

    // Verifique se já existe um evento com a mesma data de início para o mesmo usuário
    $eventoExistente = EventoModel::where('usr_responsavel', $usuarioEmail)
        ->where('start', $dataInicio)
        ->first();

    if ($eventoExistente) {
        // Se já existe um evento com a mesma data de início para o mesmo usuário
        return response()->json(['message' => 'Já existe um evento com a mesma data de início'], 500);
    }

    // Verifique se a data de início é um final de semana (sábado ou domingo)
    $dataInicioTimestamp = strtotime($dataInicio);
    $diaDaSemana = date('N', $dataInicioTimestamp);

    if ($diaDaSemana >= 6) {
        return response()->json(['message' => 'Não é permitido registrar eventos em finais de semana'], 400);
    }

    // A data de início é única e não é um final de semana, você pode criar o novo evento
    $evento = EventoModel::create([
        'title' => $request->input('title'),
        'start' => $request->input('start'),
        'description' => $request->input('description'),
        'end' => $request->input('end'),
        'status' => $request->input('status'),
        'usr_responsavel' => $usuarioEmail,
    ]);

    // Retorne uma resposta de sucesso
    return response()->json(['message' => 'Evento criado com sucesso', 'evento' => $evento], 201);
}


   /**
 * @OA\Get(
 *     path="/eventos",
 *     summary="Listar eventos",
 *     description="Listar todos os eventos",
 *     operationId="eventall",
 *     tags={"Eventos"},
 *     security={{"bearer":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Eventos",
 *         @OA\JsonContent()
 *     )
 * )
 */
    public function VisualizarEvento()
    {
        $usuarioEmail = Auth::user()->email;

        if(!$usuarioEmail) return response()->json(['message' => 'User is not authenticated'], 401);

        $eventos = EventoModel::all();
        return response()->json(['message' => 'Eventos Encontrados', 'eventos' => $eventos], 200);
    }

/**
 * @OA\Get(
 *     path="/eventos/{id}",
 *     summary="Listar um evento específico",
 *     description="Listar um evento específico",
 *     operationId="eventabyid",
 *     tags={"Eventos"},
 *     security={{"bearer":{}}},
 *     @OA\Parameter(
 *         description="Título do evento",
 *         in="path",
 *         name="id",
 *         required=true,
 *         example="evento123",
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Eventos",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Evento não encontrado ou você não tem permissão para vê-lo",
 *         @OA\JsonContent()
 *     )
 * )
 */
    public function VisualizarEventoEsp($title)
    {
        $evento = EventoModel::where('title', $title)
        ->first();
        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado ou você não tem permissão para vê-lo'], 404);
        }


        return response()->json(['message' => 'Eventos Encontrados', 'eventos' => $evento], 200);
    }

 /**
 * @OA\Delete(
 *      path="/excluir-evento/{id}",
 *      tags={"Eventos"},
 *      operationId="ApiV1DeleteUser",
 *      summary="Delete um evento",
 *      @OA\Parameter(
 *          description="Título do evento",
 *          in="path",
 *          name="id",
 *          required=true,
 *          example="evento123",
 *          @OA\Schema(
 *              type="string",
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Evento não encontrado ou você não tem permissão para excluí-lo"
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Evento deletado com sucesso"
 *      )
 * )
 */
    public function excluirEvento($title)
    {
        // Busque o evento no banco de dados com base no título e usuário autenticado
        $usuarioEmail = Auth::user()->email;
        $evento = EventoModel::where('title', $title)
            ->where('usr_responsavel', $usuarioEmail)
            ->first();

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado ou você não tem permissão para excluí-lo'], 404);
        }

        // Faça a exclusão do evento
        $evento->delete();

        // Retorne uma resposta de sucesso
        return response()->json(['message' => 'Evento deletado com sucesso']);
    }

    /**
 * @OA\Put(
 *     path="/editar-evento/{nomeEvento}",
 *     operationId="eventput2",
 *     tags={"Eventos"},
 *     summary="Put event",
 *     security={{"bearer":{}}},
 *     description="Atualizado o evento com base em seu id",
 *     @OA\Parameter(
 *         description="Nome do evento",
 *         in="path",
 *         name="nomeEvento",
 *         required=true,
 *         example="evento123",
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\MediaType(
 *                 mediaType="multipart/form-data",
 *                 @OA\Schema(
 *                     type="object",
 *                     @OA\Property(property="title", type="string", example="Evento Atualizado"),
 *                     @OA\Property(property="description", type="string", example="Descrição Atualizada"),
 *                     @OA\Property(property="start", type="string", format="date-time", example="2023-09-21T12:00:00Z"),
 *                     @OA\Property(property="end", type="string", format="date-time", example="2023-09-21T14:00:00Z"),
 *                     @OA\Property(property="status", type="boolean", example=true)
 *                 ),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Evento editado com sucesso",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(response=404, description="Evento não encontrado ou você não tem permissão para editá-lo"),
 * )
 */
    public function editarEvento(Request $request, $title)
    {
        // Busque o evento no banco de dados com base no título e usuário autenticado
        $usuarioEmail = Auth::user()->email;
        $evento = EventoModel::where('title', $title)
            ->where('usr_responsavel', $usuarioEmail)
            ->first();

        if (!$evento) {
            return response()->json(['message' => 'Evento não encontrado ou você não tem permissão para editá-lo'], 404);
        }

        // Atualize os campos do evento com base nos dados fornecidos no request
        $evento->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'status' => $request->input('status'),
        ]);

        return response()->json(['message' => 'Evento editado com sucesso']);
    }
}
