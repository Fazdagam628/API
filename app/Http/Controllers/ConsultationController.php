<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use Illuminate\Http\Response;

class ConsultationController extends Controller
{
    /** 
     * handle a consulatation request
     * 
     * @param Request $request
     * @return Response
     */

    public function requestConsultation(Request $request)
    {
        //validate the request body
        $request->validate([
            'disease_history' => 'required|string',
            'current_symptoms' => 'required|string',
        ]);

        //check if the user already has a consultation request
        $user = $request->user();
        $existingConsultation = Consultation::where('user_id', $user->id)->first();

        if ($existingConsultation) {
            return response()->json([
                'message' => 'You already have an consultation request.'
            ], 400);
        }

        //create a new consultation request
        $consultation = Consultation::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'disease_history' => $request->disease_history,
            'current_symptoms' => $request->current_symptoms,
        ]);

        return response()->json([
            'message' => 'Request consultation sent successfully',
        ], 200);
    }

    /**
     * 
     * get the current consultation status for the authenticated user
     * 
     * @param Request $request
     * @return Response
     */

     public function getConsultationStatus(Request $request)
     {
        //fetch the user's consultation request
        $user = $request->user();
        $consultation = Consultation::where('user_id', $user->id)->first();

        if (!$consultation) {
            return response()->json([
                'message' => 'No consultation found for the user',
            ], 400);
        }

        return response()->json([
            'consultation' => [
                'id' => $consultation->id,
                'status' => $consultation->status,
                'disease_history' => $consultation->disease_history,
                'current_symptoms' => $consultation->current_symptoms,
                'doctor_notes' => $consultation->doctor_notes,
                'doctor' => $consultation->doctor ? [
                    'id' => $consultation->doctor->id,
                    'name' => $consultation->doctor->name,
                    //Add other doctor field if necessary
                ] : null,
            ],
        ], 200);
     }
}
