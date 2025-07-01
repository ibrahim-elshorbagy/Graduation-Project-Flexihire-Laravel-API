<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillsJobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Backend Development
            ['name' => 'PHP'],
            ['name' => 'Node.js'],
            ['name' => 'Python'],
            ['name' => 'Java'],
            ['name' => 'Ruby'],
            ['name' => 'C#'],
            ['name' => 'Go'],
            ['name' => 'Laravel'],
            ['name' => 'Django'],
            ['name' => 'Express.js'],
            ['name' => 'Spring Boot'],
            ['name' => 'Ruby on Rails'],
            ['name' => 'ASP.NET Core'],
            ['name' => 'Flask'],
            ['name' => 'FastAPI'],
            
            // Frontend Development
            ['name' => 'JavaScript'],
            ['name' => 'TypeScript'],
            ['name' => 'HTML'],
            ['name' => 'CSS'],
            ['name' => 'React'],
            ['name' => 'Angular'],
            ['name' => 'Vue.js'],
            ['name' => 'Next.js'],
            ['name' => 'SASS/SCSS'],
            ['name' => 'Tailwind CSS'],
            ['name' => 'Bootstrap'],
            ['name' => 'Redux'],
            ['name' => 'Webpack'],
            ['name' => 'Vite'],
            ['name' => 'JQuery'],
            
            // Database
            ['name' => 'MySQL'],
            ['name' => 'PostgreSQL'],
            ['name' => 'MongoDB'],
            ['name' => 'Redis'],
            ['name' => 'Firebase'],
            ['name' => 'SQL Server'],
            ['name' => 'Oracle'],
            ['name' => 'Cassandra'],
            ['name' => 'DynamoDB'],
            
            // DevOps
            ['name' => 'Docker'],
            ['name' => 'AWS'],
            ['name' => 'Azure'],
            ['name' => 'Google Cloud'],
            ['name' => 'Git'],
            ['name' => 'GitHub Actions'],
            ['name' => 'GitLab CI/CD'],
            ['name' => 'Prometheus'],
            ['name' => 'Apache'],
            ['name' => 'Nginx'],
            
            // Mobile Development
            ['name' => 'Swift'],
            ['name' => 'Kotlin'],
            ['name' => 'Flutter'],
            ['name' => 'React Native'],
            ['name' => 'Android SDK'],
            ['name' => 'iOS Development'],
            ['name' => 'Ionic'],
            
            // Cybersecurity
            ['name' => 'Network Security'],
            ['name' => 'Penetration Testing'],
            ['name' => 'Cryptography'],
            ['name' => 'Security Auditing'],
            ['name' => 'Ethical Hacking'],
            ['name' => 'Security Compliance'],
            ['name' => 'Incident Response'],
            
            // Data Science
            ['name' => 'Machine Learning'],
            ['name' => 'Data Analysis'],
            ['name' => 'Artificial Intelligence'],
            ['name' => 'Python for Data Science'],
            ['name' => 'R Programming'],
            ['name' => 'PyTorch'],
            ['name' => 'Pandas'],
            ['name' => 'NumPy'],
            ['name' => 'Data Visualization'],
            ['name' => 'Big Data'],
            
            // Blockchain
            ['name' => 'Blockchain'],
            ['name' => 'Smart Contracts'],
            ['name' => 'Web3.js'],
            ['name' => 'Cryptocurrency'],
            
            // UX/UI
            ['name' => 'UI Design'],
            ['name' => 'UX Design'],
            ['name' => 'User Research'],
            ['name' => 'Wireframing'],
            ['name' => 'Prototyping'],
            ['name' => 'Figma'],
            ['name' => 'Adobe XD'],
            ['name' => 'Sketch'],
        ];

        foreach ($skills as $skill) {
            DB::table('skills')->insert($skill);
        }

           $jobs = [
            // Backend Development Jobs
            'Backend Developer',
            'PHP Developer',
            'Python Developer',
            'Java Developer',
            'Ruby Developer',
            'C# Developer',
            'Go Developer',
            'Laravel Developer',
            'Django Developer',
            'Express.js Developer',
            'API Engineer',
            'RESTful API Developer',
            'Microservice Architect',
            
            // Frontend Development Jobs
            'Frontend Developer',
            'JavaScript Developer',
            'React Developer',
            'Angular Developer',
            'Vue.js Developer',
            'Next.js Developer',
            'TypeScript Developer',
            'UI Developer',
            'CSS Specialist',
            'HTML/CSS Developer',
            'Web Developer',
            
            // Full Stack Development
            'Full Stack Developer',
            'MERN Stack Developer',
            'MEAN Stack Developer',
            'LAMP Stack Developer',
            'Software Engineer',
            
            // Database Jobs
            'Database Administrator',
            'Database Developer',
            'SQL Developer',
            'NoSQL Developer',
            'Database Architect',
            'MongoDB Developer',
            'PostgreSQL Administrator',
            'MySQL Administrator',
            
            // DevOps Jobs
            'DevOps Engineer',
            'Cloud Engineer',
            'Site Reliability Engineer',
            'Infrastructure Engineer',
            'CI/CD Engineer',
            'Git Administrator',
            'Cloud Solutions Architect',
            'Cloud Consultant',
            'AWS Specialist',
            'Azure Engineer',
            'Google Cloud Engineer',
            'Systems Administrator',
            
            // Mobile Development Jobs
            'Mobile App Developer',
            'iOS Developer',
            'Android Developer',
            'Flutter Developer',
            'React Native Developer',
            'Swift Developer',
            'Kotlin Developer',
            'Mobile UI Designer',
            
            // Cybersecurity Jobs
            'Security Analyst',
            'Cybersecurity Specialist',
            'Security Engineer',
            'Penetration Tester',
            'Security Consultant',
            'Network Security Engineer',
            'Security Architect',
            'Ethical Hacker',
            
            // Data Science Jobs
            'Data Scientist',
            'Machine Learning Engineer',
            'AI Engineer',
            'Data Analyst',
            'Business Intelligence Developer',
            'Research Scientist',
            'Big Data Engineer',
            'Analytics Engineer',
            'Statistician',
            'NLP Engineer',
            
            // Blockchain Jobs
            'Blockchain Developer',
            'Smart Contract Developer',
            'Blockchain Engineer',
            'Cryptocurrency Specialist',
            'Blockchain Solution Architect',
            
            // UX/UI Jobs
            'UI/UX Designer',
            'User Experience Designer',
            'User Interface Designer',
            'Product Designer',
            'Interaction Designer',
            'Visual Designer',
            'Web Designer',
            'UX Researcher',
            
            // Other Tech Jobs
            'QA Engineer',
            'Test Engineer',
            'Product Manager',
            'Project Manager',
            'Technical Writer',
            'Agile Coach',
            'Technical Support Engineer',
            'Enterprise Architect',
            'E-commerce Specialist',
            'Performance Analyst',
            'Network Engineer',
            'Network Architect',
            'Solutions Architect',
            'Business Analyst',
            'Digital Designer',
            'Game Developer',
            'SEO Specialist',
            'Marketing Specialist',
            'Content Writer',
            'Customer Success Manager'
        ];

        foreach ($jobs as $job) {
            DB::table('our_jobs_title')->insert(['name' => $job]);
        }
    }
}
