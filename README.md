# SmartTeach Planner

<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-green" alt="License"></a>
</p>

---

## About SmartTeach Planner

**SmartTeach Planner** is a web-based system for teachers that automates lesson plan analysis and assessment generation using AI. The system allows teachers to:

- Upload **lesson plan PDFs**
- Extract text from uploaded PDFs
- Integrate AI for content analysis
- Automatically generate:
  - Learning objectives
  - Classroom activities
  - Quiz questions (MCQ, Identification, Essay)
  - Performance tasks
  - Rubrics
- Download generated outputs as structured PDFs

The system reduces lesson preparation time and improves instructional quality through AI integration.

---

## System Objectives

### General Objective

To design and develop an AI-integrated web-based lesson planning and assessment generation system.

### Specific Objectives

1. Allow teachers to upload lesson plan PDFs.  
2. Extract text content from uploaded PDFs.  
3. Integrate AI for content analysis and generation.  
4. Automatically generate quizzes, activities, and assessments.  
5. Allow downloading generated outputs as PDF.  


## Tech Stack

- **Backend:** Laravel 12  
- **Frontend:** Vite + TailwindCSS  
- **Database:** MySQL / MariaDB  
- **PDF Extraction:** `spatie/pdf-to-text`  
- **AI Integration:** Any AI API capable of text analysis and question generation  
- **Authentication:** Laravel Breeze or Sanctum (optional)
