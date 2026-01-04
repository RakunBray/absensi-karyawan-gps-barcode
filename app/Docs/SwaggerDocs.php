<?php

namespace App\Docs;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Absensi Karyawan GPS & Barcode API",
 *      description="API Documentation untuk Sistem Absensi Karyawan dengan GPS dan Barcode Scanner",
 *      @OA\Contact(
 *          email="admin@absensi.com",
 *          name="Admin Absensi"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Enter your bearer token in the format: Bearer {token}"
 * )
 *
 * -- TAGS DEFINITION --
 * @OA\Tag(name="Admin - Employees", description="Manajemen karyawan")
 * @OA\Tag(name="Admin - Barcodes", description="Manajemen barcode lokasi")
 * @OA\Tag(name="Admin - Attendances", description="Manajemen absensi")
 * @OA\Tag(name="Admin - Master Data", description="Manajemen master data")
 * @OA\Tag(name="Admin - Import/Export", description="Import dan export data")
 * @OA\Tag(name="User - Dashboard", description="Dashboard karyawan")  
 * @OA\Tag(name="User - Attendance", description="Absensi dan pengajuan izin")
 */

class SwaggerDocs
{
    // This class is used solely for Swagger documentation
    // No actual implementation needed
}
