<?php

namespace App\Http\Controllers;

/**
 * Common Swagger Schemas
 * 
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="string", example="01JKNSK1234567890"),
 *     @OA\Property(property="nip", type="string", example="12345678"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="group", type="string", enum={"user", "admin", "superadmin"}),
 *     @OA\Property(property="status", type="string", example="approved"),
 *     @OA\Property(property="phone", type="string", example="081234567890"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female"}),
 *     @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="birth_place", type="string", example="Jakarta"),
 *     @OA\Property(property="address", type="string", example="Jl. Example No. 123"),
 *     @OA\Property(property="city", type="string", example="Jakarta"),
 *     @OA\Property(property="education_id", type="integer", example=1),
 *     @OA\Property(property="division_id", type="integer", example=1),
 *     @OA\Property(property="job_title_id", type="integer", example=1),
 *     @OA\Property(property="email_verified_at", type="string", format="datetime", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime")
 * )
 * 
 * @OA\Schema(
 *     schema="Attendance",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="string", example="01JKNSK1234567890"),
 *     @OA\Property(property="barcode_id", type="integer", example=1),
 *     @OA\Property(property="date", type="string", format="date", example="2024-01-04"),
 *     @OA\Property(property="time_in", type="string", format="time", example="08:00:00"),
 *     @OA\Property(property="time_out", type="string", format="time", example="17:00:00", nullable=true),
 *     @OA\Property(property="shift_id", type="integer", example=1, nullable=true),
 *     @OA\Property(property="latitude", type="number", format="double", example=-6.2088),
 *     @OA\Property(property="longitude", type="number", format="double", example=106.8456),
 *     @OA\Property(property="status", type="string", example="present"),
 *     @OA\Property(property="note", type="string", nullable=true),
 *     @OA\Property(property="attachment", type="string", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime")
 * )
 * 
 * @OA\Schema(
 *     schema="Barcode",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Kantor Pusat"),
 *     @OA\Property(property="value", type="string", example="OFFICE_MAIN_001"),
 *     @OA\Property(property="latitude", type="number", format="double", example=-6.2088),
 *     @OA\Property(property="longitude", type="number", format="double", example=106.8456),
 *     @OA\Property(property="radius", type="number", format="double", example=100),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime")
 * )
 */
abstract class Controller
{
    //
}
