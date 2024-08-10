<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocWithConfResource;
use App\Models\Document;
use App\Models\DocumentConfiguration;

class DocumentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/documents",
     *     summary="Get a list of documents",
     *     tags={"Documents"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function documents()
    {
        return DocumentResource::collection(Document::all());
    }

     /**
     * @OA\Get(
     *     path="/api/v1/documents/{id}",
     *     summary="Get a specific document",
     *     tags={"Documents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="documentName", type="string"),
     *             @OA\Property(
     *                 property="fields",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="field_seq", type="integer"),
     *                     @OA\Property(property="is_mandatory", type="integer"),
     *                     @OA\Property(property="field_type", type="integer"),
     *                     @OA\Property(property="field_name", type="string"),
     *                     @OA\Property(property="document_id", type="integer"),
     *                     @OA\Property(property="select_values", type="string", format="json")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function getDocumentById($document_id)
    {
        $document = Document::with('documentConfiguration')->find($document_id);
        if (!$document) {
            return response()->json(['message' => 'document_not_found'], 404);
        }
        return new DocWithConfResource($document);
    }

    
 /**
     * @OA\Post(
     *     path="/api/v1/documents/create",
     *     tags={"Documents"},
     *     summary="new document",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"document_name"},
     *             @OA\Property(property="document_name", type="string", example="Apple"),
     *             @OA\Property(property="content", type="string",  example="this id good fruit"),
     *             @OA\Property(property="price", type="integer",  example="3000")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful create document",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *     )
     * )
     */
    public function create(DocumentRequest $request)
    {
        $document = Document::create(["document_name" => $request->document_name]);

        $form_values = $request->form_values;

        foreach ($form_values as $value) {
            $document->documentConfiguration()->create([
                'field_seq' => $value["field_seq"],
                'is_mandatory' => $value["is_mandatory"],
                'field_type' => $value["field_type"],
                'field_name' => $value["field_name"],
                'select_values' => json_encode($value["select_values"]),
            ]);
        }

        return $document;
    }


}
