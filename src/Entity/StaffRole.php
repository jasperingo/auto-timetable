<?php
namespace App\Entity;

enum StaffRole: string {
  case Admin = 'admin';
  case ExamOfficer = 'exam_officer';
  case CourseAdviser = 'course_adviser';
  case Invigilator = 'invigilator';
}
