<?php

namespace Database\Seeders;

use App\Models\JobList;
use App\Models\User;
use App\Models\User\JobApply;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Carbon;

class CompanyJobUserSeeder extends Seeder
{
    /**
     * Seed the application's database with companies, jobs, and users.
     */
    public function run(): void
    {
        // Ensure roles exist
        $companyRole = Role::firstOrCreate(['name' => 'company']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create 10 company accounts
        $companies = [
            [
                'name' => 'Facebook',
                'image' => 'https://cdn-icons-png.flaticon.com/512/5968/5968764.png',
                'description' => 'Facebook is a social media and social networking service owned by American company Meta Platforms.',
                'location' => 'Menlo Park, California'
            ],
            [
                'name' => 'X (Twitter)',
                'image' => 'https://cdn-icons-png.flaticon.com/512/5968/5968958.png',
                'description' => 'X (formerly Twitter) is an online social media and social networking service operated by X Corp.',
                'location' => 'San Francisco, California'
            ],
            [
                'name' => 'YouTube',
                'image' => 'https://cdn-icons-png.flaticon.com/512/5968/5968852.png',
                'description' => 'YouTube is an online video sharing and social media platform owned by Google.',
                'location' => 'San Bruno, California'
            ],
            [
                'name' => 'Microsoft',
                'image' => 'https://cdn-icons-png.flaticon.com/512/732/732221.png',
                'description' => 'Microsoft Corporation is an American multinational technology corporation that produces computer software, consumer electronics, personal computers, and related services.',
                'location' => 'Redmond, Washington'
            ],
            [
                'name' => 'Apple',
                'image' => 'https://cdn-icons-png.flaticon.com/512/0/747.png',
                'description' => 'Apple Inc. is an American multinational technology company that specializes in consumer electronics, software and online services.',
                'location' => 'Cupertino, California'
            ],
            [
                'name' => 'Amazon',
                'image' => 'https://cdn-icons-png.flaticon.com/512/14079/14079391.png',
                'description' => 'Amazon.com, Inc. is an American multinational technology company focusing on e-commerce, cloud computing, online advertising, digital streaming, and artificial intelligence.',
                'location' => 'Seattle, Washington'
            ],
            [
                'name' => 'Netflix',
                'image' => 'https://cdn-icons-png.flaticon.com/512/732/732228.png',
                'description' => 'Netflix, Inc. is an American subscription video on-demand over-the-top streaming service and production company.',
                'location' => 'Los Gatos, California'
            ],
            [
                'name' => 'Google',
                'image' => 'https://cdn-icons-png.flaticon.com/512/300/300221.png',
                'description' => 'Google LLC is an American multinational technology company that specializes in Internet-related services and products.',
                'location' => 'Mountain View, California'
            ],
            [
                'name' => 'Tesla',
                'image' => 'https://cdn-icons-png.flaticon.com/512/16183/16183663.png',
                'description' => 'Tesla, Inc. is an American multinational automotive and clean energy company that designs and manufactures electric vehicles, battery energy storage, and solar panels.',
                'location' => 'Austin, Texas'
            ],
            [
                'name' => 'Adobe',
                'image' => 'https://cdn-icons-png.flaticon.com/512/888/888835.png',
                'description' => 'Adobe Inc. is an American multinational computer software company that focuses on software for content creation, and more recently digital marketing.',
                'location' => 'San Jose, California'
            ],
        ];

        $companyIds = [];

        foreach ($companies as $index => $companyData) {
            $company = User::create([
                'first_name' => $companyData['name'],
                'last_name' => 'Inc',
                'email' => strtolower(str_replace(' ', '', $companyData['name'])) . '@company.com',
                'password' => Hash::make('password'),
                'image_url' => $companyData['image'],
                'description' => $companyData['description'],
                'location' => $companyData['location'],
            ]);
            
            $company->assignRole($companyRole);
            $companyIds[] = $company->id;
            
            $this->command->info("Created company: {$companyData['name']}");
        }

        // Create 20 unique jobs (2 per company)
        $jobDescriptions = [
            // Job 1: Senior Frontend Developer
            [
                'title' => 'Senior Frontend Developer',
                'description' => '<h2>About This Role</h2>
<p>We are looking for a Senior Frontend Developer to join our dynamic team. You will be responsible for building and maintaining user interfaces for our web applications.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Develop new user-facing features using React.js and modern frontend technologies</li>
  <li>Build reusable components and libraries for future use</li>
  <li>Translate designs and wireframes into high-quality code</li>
  <li>Optimize applications for maximum speed and scalability</li>
  <li>Collaborate with back-end developers and web designers</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>5+ years of experience in frontend development</li>
  <li>Proficiency with JavaScript, HTML5, CSS3</li>
  <li>Experience with React.js or similar frontend frameworks</li>
  <li>Understanding of responsive design principles</li>
  <li>Knowledge of modern frontend build pipelines and tools</li>
</ul>',
                'skills' => ['React', 'JavaScript', 'HTML', 'CSS', 'Webpack'],
                'min_salary' => 80000,
                'max_salary' => 120000,
                'payment_period' => 'yearly',
            ],
            
            // Job 2: Junior React Developer
            [
                'title' => 'Junior React Developer',
                'description' => '<h2>About This Role</h2>
<p>We are seeking a motivated Junior React Developer to join our growing team. This is an excellent opportunity for someone starting their career in frontend development.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Develop user interface components using React.js</li>
  <li>Implement responsive designs with modern CSS frameworks</li>
  <li>Participate in code reviews and follow best practices</li>
  <li>Collaborate with senior developers to learn and grow</li>
  <li>Write clean, maintainable code</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>1-2 years of experience with React.js</li>
  <li>Basic knowledge of JavaScript, HTML5, CSS3</li>
  <li>Familiarity with Git version control</li>
  <li>Understanding of responsive web design</li>
  <li>Eagerness to learn and grow in a team environment</li>
</ul>',
                'skills' => ['React', 'JavaScript', 'HTML', 'CSS', 'Git'],
                'min_salary' => 45000,
                'max_salary' => 65000,
                'payment_period' => 'yearly',
            ],
            
            // Job 3: UI/UX Designer
            [
                'title' => 'UI/UX Designer',
                'description' => '<h2>About This Role</h2>
<p>We are seeking a talented UI/UX Designer to create amazing user experiences. The ideal candidate should have an eye for clean and artful design, possess superior UI skills, and be able to translate high-level requirements into interaction flows and artifacts.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Create user-centered designs by understanding business requirements, user feedback, and user research insights</li>
  <li>Create wireframes, prototypes, and design mockups</li>
  <li>Find creative solutions to UX problems</li>
  <li>Collaborate with developers to implement design solutions</li>
  <li>Conduct usability testing and gather feedback</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>3+ years of experience in UI/UX design</li>
  <li>Proficiency with design tools such as Figma, Adobe XD, or Sketch</li>
  <li>Strong portfolio demonstrating UI/UX capabilities</li>
  <li>Understanding of interaction design and information architecture</li>
  <li>Knowledge of user research and usability principles</li>
</ul>',
                'skills' => ['UI Design', 'UX Design', 'Figma', 'Adobe XD', 'Wireframing'],
                'min_salary' => 70000,
                'max_salary' => 100000,
                'payment_period' => 'yearly',
            ],
            
            // Job 4: Junior UX/UI Designer
            [
                'title' => 'Junior UX/UI Designer',
                'description' => '<h2>About This Role</h2>
<p>Join our design team as a Junior UX/UI Designer! This role is perfect for a creative individual looking to start their career in digital design and user experience.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Assist in creating wireframes and prototypes</li>
  <li>Support user research and usability testing</li>
  <li>Create visual designs under senior designer guidance</li>
  <li>Help maintain design systems and style guides</li>
  <li>Collaborate with development team on design implementation</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>0-2 years of experience in UI/UX design</li>
  <li>Basic knowledge of Figma or Adobe XD</li>
  <li>Understanding of design principles and typography</li>
  <li>Portfolio showcasing design projects or personal work</li>
  <li>Strong communication and collaboration skills</li>
</ul>',
                'skills' => ['UI Design', 'UX Design', 'Figma', 'Prototyping', 'User Research'],
                'min_salary' => 40000,
                'max_salary' => 60000,
                'payment_period' => 'yearly',
            ],
            
            // Job 5: Senior Backend Engineer
            [
                'title' => 'Senior Backend Engineer',
                'description' => '<h2>About This Role</h2>
<p>We\'re looking for a Senior Backend Engineer to design, develop, and maintain our server-side applications. You will be working on critical infrastructure that powers our products.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Design and implement robust, scalable backend services</li>
  <li>Write clean, maintainable, and efficient code</li>
  <li>Optimize application performance and responsiveness</li>
  <li>Implement security and data protection measures</li>
  <li>Collaborate with frontend developers and other team members</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>5+ years of experience in backend development</li>
  <li>Proficiency with one or more backend languages (Node.js, Python, Java, etc.)</li>
  <li>Experience with database design and optimization</li>
  <li>Knowledge of cloud services (AWS, Azure, or GCP)</li>
  <li>Understanding of server-side templating languages</li>
</ul>',
                'skills' => ['Node.js', 'Python', 'AWS', 'MySQL', 'PostgreSQL'],
                'min_salary' => 90000,
                'max_salary' => 140000,
                'payment_period' => 'yearly',
            ],
            
            // Job 6: Junior PHP Developer
            [
                'title' => 'Junior PHP Developer',
                'description' => '<h2>About This Role</h2>
<p>We are looking for an enthusiastic Junior PHP Developer to join our backend team. This position offers great learning opportunities and growth potential.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Develop web applications using PHP and Laravel framework</li>
  <li>Write clean, well-documented code</li>
  <li>Participate in code reviews and testing</li>
  <li>Work with databases to store and retrieve data</li>
  <li>Debug and troubleshoot application issues</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>1-2 years of experience with PHP</li>
  <li>Basic knowledge of Laravel framework</li>
  <li>Understanding of MySQL databases</li>
  <li>Familiarity with HTML, CSS, and JavaScript</li>
  <li>Strong problem-solving skills and attention to detail</li>
</ul>',
                'skills' => ['PHP', 'Laravel', 'MySQL', 'HTML', 'CSS'],
                'min_salary' => 42000,
                'max_salary' => 62000,
                'payment_period' => 'yearly',
            ],
            
            // Job 7: Database Administrator
            [
                'title' => 'Database Administrator',
                'description' => '<h2>About This Role</h2>
<p>We are seeking a skilled Database Administrator to maintain and secure our database systems. You will be responsible for the performance, integrity, and security of databases.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Install and maintain database servers and applications</li>
  <li>Design and implement database strategies, schemas, and models</li>
  <li>Monitor database performance and optimize database structures</li>
  <li>Implement and maintain database security measures</li>
  <li>Create disaster recovery plans to prevent data loss</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>4+ years of experience as a Database Administrator</li>
  <li>Expertise in SQL and database management systems (MySQL, PostgreSQL, etc.)</li>
  <li>Experience with database design, documentation, and optimization</li>
  <li>Knowledge of data backup, recovery, and security processes</li>
  <li>Understanding of GDPR and data privacy practices</li>
</ul>',
                'skills' => ['SQL', 'MySQL', 'PostgreSQL', 'Database Design', 'Oracle'],
                'min_salary' => 85000,
                'max_salary' => 130000,
                'payment_period' => 'yearly',
            ],
            
            // Job 8: Junior Database Developer
            [
                'title' => 'Junior Database Developer',
                'description' => '<h2>About This Role</h2>
<p>Join our data team as a Junior Database Developer! This role offers hands-on experience with database design, development, and optimization.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Assist in database design and schema development</li>
  <li>Write and optimize SQL queries</li>
  <li>Support data migration and integration projects</li>
  <li>Help maintain database documentation</li>
  <li>Learn database performance tuning techniques</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>1-2 years of experience with SQL</li>
  <li>Basic knowledge of MySQL or PostgreSQL</li>
  <li>Understanding of database normalization</li>
  <li>Familiarity with data modeling concepts</li>
  <li>Strong analytical and problem-solving skills</li>
</ul>',
                'skills' => ['SQL', 'MySQL', 'PostgreSQL', 'Database Design', 'Data Analysis'],
                'min_salary' => 48000,
                'max_salary' => 68000,
                'payment_period' => 'yearly',
            ],
            
            // Job 9: DevOps Engineer
            [
                'title' => 'DevOps Engineer',
                'description' => '<h2>About This Role</h2>
<p>We are looking for a DevOps Engineer to help us build and maintain our infrastructure and deployment systems. You will work on automating processes and ensuring our systems are scalable and reliable.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Implement and automate CI/CD pipelines</li>
  <li>Manage cloud infrastructure and containerized environments</li>
  <li>Monitor system performance and troubleshoot issues</li>
  <li>Implement security best practices and ensure compliance</li>
  <li>Collaborate with development teams to improve deployment processes</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>3+ years of experience in DevOps or Site Reliability Engineering</li>
  <li>Experience with infrastructure as code tools (Terraform, CloudFormation)</li>
  <li>Knowledge of containerization and orchestration (Docker, Kubernetes)</li>
  <li>Familiarity with cloud platforms (AWS, Azure, GCP)</li>
  <li>Understanding of networking and security principles</li>
</ul>',
                'skills' => ['Docker', 'Kubernetes', 'AWS', 'Terraform', 'GitHub Actions'],
                'min_salary' => 85000,
                'max_salary' => 130000,
                'payment_period' => 'yearly',
            ],
            
            // Job 10: Junior DevOps Engineer
            [
                'title' => 'Junior DevOps Engineer',
                'description' => '<h2>About This Role</h2>
<p>Start your DevOps career with us! This junior position offers excellent learning opportunities in cloud infrastructure, automation, and deployment processes.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Assist in maintaining CI/CD pipelines</li>
  <li>Help monitor system performance and logs</li>
  <li>Support cloud infrastructure management</li>
  <li>Learn automation tools and scripting</li>
  <li>Participate in incident response and troubleshooting</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>0-2 years of experience in DevOps or IT operations</li>
  <li>Basic knowledge of Linux systems</li>
  <li>Familiarity with Docker and containerization</li>
  <li>Understanding of Git version control</li>
  <li>Eagerness to learn cloud platforms (AWS, Azure, or GCP)</li>
</ul>',
                'skills' => ['Docker', 'Git', 'AWS', 'Apache', 'Nginx'],
                'min_salary' => 50000,
                'max_salary' => 70000,
                'payment_period' => 'yearly',
            ],
            
            // Job 11: Site Reliability Engineer
            [
                'title' => 'Site Reliability Engineer',
                'description' => '<h2>About This Role</h2>
<p>We are seeking a Site Reliability Engineer to focus on availability, latency, performance, and capacity of our systems. You will apply software engineering principles to our infrastructure and operations problems.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Design, build, and maintain scalable and reliable infrastructure</li>
  <li>Automate operations and processes using software engineering practices</li>
  <li>Implement monitoring, alerting, and logging systems</li>
  <li>Respond to and resolve production incidents</li>
  <li>Collaborate with development teams to improve system reliability</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>4+ years of experience in software engineering or systems administration</li>
  <li>Strong programming skills in one or more languages</li>
  <li>Experience with cloud infrastructure and containerization</li>
  <li>Knowledge of monitoring systems and observability practices</li>
  <li>Understanding of network protocols and security</li>
</ul>',
                'skills' => ['Python', 'Kubernetes', 'Prometheus', 'AWS', 'Incident Response'],
                'min_salary' => 90000,
                'max_salary' => 140000,
                'payment_period' => 'yearly',
            ],
            
            // Job 12: Data Scientist
            [
                'title' => 'Data Scientist',
                'description' => '<h2>About This Role</h2>
<p>We are looking for a Data Scientist to analyze complex data sets and extract meaningful insights. You will help us make data-driven decisions and develop predictive models.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Analyze large data sets to identify patterns and trends</li>
  <li>Develop predictive models and machine learning algorithms</li>
  <li>Create data visualizations and reports</li>
  <li>Collaborate with product and engineering teams</li>
  <li>Present findings to stakeholders</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>3+ years of experience in data science or related field</li>
  <li>Proficiency in Python, R, or similar languages</li>
  <li>Experience with data visualization tools</li>
  <li>Knowledge of machine learning techniques</li>
  <li>Strong mathematical and statistical skills</li>
</ul>',
                'skills' => ['Python', 'Machine Learning', 'Pandas', 'Data Visualization', 'R Programming'],
                'min_salary' => 90000,
                'max_salary' => 140000,
                'payment_period' => 'yearly',
            ],
            
            // Job 13: Machine Learning Engineer
            [
                'title' => 'Machine Learning Engineer',
                'description' => '<h2>About This Role</h2>
<p>We are seeking a Machine Learning Engineer to design and implement machine learning systems. You will collaborate with data scientists to deploy models into production.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Design and implement machine learning systems</li>
  <li>Research and implement appropriate ML algorithms</li>
  <li>Develop systems to efficiently process large amounts of data</li>
  <li>Optimize existing machine learning models</li>
  <li>Collaborate with data scientists and engineers</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>4+ years of experience in machine learning or related field</li>
  <li>Strong programming skills in Python</li>
  <li>Experience with machine learning frameworks (TensorFlow, PyTorch)</li>
  <li>Knowledge of data structures, data modeling, and software architecture</li>
  <li>Understanding of distributed computing</li>
</ul>',
                'skills' => ['Python', 'TensorFlow', 'PyTorch', 'Machine Learning', 'Big Data'],
                'min_salary' => 100000,
                'max_salary' => 150000,
                'payment_period' => 'yearly',
            ],
            
            // Job 14: Product Manager
            [
                'title' => 'Product Manager',
                'description' => '<h2>About This Role</h2>
<p>We are looking for a Product Manager to help us define and deliver innovative products. You will work with cross-functional teams to ensure we\'re building the right product for our users.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Define product vision, strategy, and roadmap</li>
  <li>Gather and prioritize product requirements</li>
  <li>Work closely with engineering, design, and marketing teams</li>
  <li>Define success metrics and monitor product performance</li>
  <li>Conduct market research and competitive analysis</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>3+ years of experience in product management</li>
  <li>Strong problem-solving and analytical skills</li>
  <li>Excellent communication and leadership abilities</li>
  <li>Understanding of technology and user experience design</li>
  <li>Experience with agile development methodologies</li>
</ul>',
                'skills' => ['Product Strategy', 'Agile', 'User Stories', 'Market Research', 'Analytics'],
                'min_salary' => 90000,
                'max_salary' => 140000,
                'payment_period' => 'yearly',
            ],
            
            // Job 15: Mobile App Developer
            [
                'title' => 'Mobile App Developer',
                'description' => '<h2>About This Role</h2>
<p>Join our mobile team as a Mobile App Developer! You will be responsible for creating high-quality mobile applications for iOS and Android platforms.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Develop native and cross-platform mobile applications</li>
  <li>Collaborate with designers to implement intuitive user interfaces</li>
  <li>Integrate mobile apps with backend services and APIs</li>
  <li>Optimize applications for performance and user experience</li>
  <li>Test and debug mobile applications across different devices</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>3+ years of experience in mobile app development</li>
  <li>Proficiency in Swift, Kotlin, or React Native</li>
  <li>Experience with mobile app architecture and design patterns</li>
  <li>Knowledge of RESTful APIs and third-party integrations</li>
  <li>Understanding of mobile app store guidelines and deployment</li>
</ul>',
                'skills' => ['Swift', 'Kotlin', 'React Native', 'iOS Development', 'Android SDK'],
                'min_salary' => 80000,
                'max_salary' => 125000,
                'payment_period' => 'yearly',
            ],
            
            // Job 16: Cybersecurity Analyst
            [
                'title' => 'Cybersecurity Analyst',
                'description' => '<h2>About This Role</h2>
<p>We are seeking a Cybersecurity Analyst to protect our organization\'s digital assets. You will monitor, detect, and respond to security threats and vulnerabilities.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Monitor security events and analyze potential threats</li>
  <li>Conduct vulnerability assessments and penetration testing</li>
  <li>Implement and maintain security tools and policies</li>
  <li>Respond to security incidents and breaches</li>
  <li>Create security reports and recommendations</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>3+ years of experience in cybersecurity</li>
  <li>Knowledge of security frameworks and compliance standards</li>
  <li>Experience with security tools and technologies</li>
  <li>Understanding of network security and cryptography</li>
  <li>Strong analytical and problem-solving skills</li>
</ul>',
                'skills' => ['Network Security', 'Penetration Testing', 'Cryptography', 'Security Auditing', 'Incident Response'],
                'min_salary' => 75000,
                'max_salary' => 115000,
                'payment_period' => 'yearly',
            ],
            
            // Job 17: QA Engineer
            [
                'title' => 'QA Engineer',
                'description' => '<h2>About This Role</h2>
<p>We are looking for a Quality Assurance Engineer to ensure the quality and reliability of our software products through comprehensive testing strategies.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Design and execute test plans and test cases</li>
  <li>Perform manual and automated testing</li>
  <li>Identify, document, and track software defects</li>
  <li>Collaborate with development teams to resolve issues</li>
  <li>Continuously improve testing processes and methodologies</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>3+ years of experience in software testing</li>
  <li>Knowledge of testing methodologies and tools</li>
  <li>Experience with automated testing frameworks</li>
  <li>Understanding of SDLC and agile methodologies</li>
  <li>Strong attention to detail and analytical skills</li>
</ul>',
                'skills' => ['Git', 'JavaScript', 'Python', 'Agile', 'Test Automation'],
                'min_salary' => 65000,
                'max_salary' => 95000,
                'payment_period' => 'yearly',
            ],
            
            // Job 18: Full Stack Developer
            [
                'title' => 'Full Stack Developer',
                'description' => '<h2>About This Role</h2>
<p>We are seeking a versatile Full Stack Developer to work on both frontend and backend technologies. You will be involved in the complete software development lifecycle.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Develop web applications using modern frontend and backend technologies</li>
  <li>Design and implement RESTful APIs</li>
  <li>Work with databases and optimize queries</li>
  <li>Collaborate with cross-functional teams</li>
  <li>Participate in code reviews and maintain code quality</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>4+ years of experience in full stack development</li>
  <li>Proficiency in JavaScript, React, and Node.js</li>
  <li>Experience with database technologies (SQL and NoSQL)</li>
  <li>Knowledge of version control systems and deployment processes</li>
  <li>Understanding of web security and performance optimization</li>
</ul>',
                'skills' => ['JavaScript', 'React', 'Node.js', 'MongoDB', 'Express.js'],
                'min_salary' => 85000,
                'max_salary' => 130000,
                'payment_period' => 'yearly',
            ],
            
            // Job 19: Cloud Solutions Architect
            [
                'title' => 'Cloud Solutions Architect',
                'description' => '<h2>About This Role</h2>
<p>We are looking for a Cloud Solutions Architect to design and implement scalable cloud infrastructure solutions. You will lead cloud adoption initiatives and ensure best practices.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Design cloud architecture solutions for business requirements</li>
  <li>Lead cloud migration and modernization projects</li>
  <li>Establish cloud governance and security standards</li>
  <li>Optimize cloud costs and performance</li>
  <li>Mentor teams on cloud technologies and best practices</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>5+ years of experience in cloud architecture</li>
  <li>Expertise in AWS, Azure, or Google Cloud Platform</li>
  <li>Knowledge of infrastructure as code and automation tools</li>
  <li>Experience with containerization and microservices</li>
  <li>Strong leadership and communication skills</li>
</ul>',
                'skills' => ['AWS', 'Azure', 'Google Cloud', 'Terraform', 'Kubernetes'],
                'min_salary' => 120000,
                'max_salary' => 180000,
                'payment_period' => 'yearly',
            ],
            
            // Job 20: Technical Writer
            [
                'title' => 'Technical Writer',
                'description' => '<h2>About This Role</h2>
<p>We are seeking a Technical Writer to create clear, comprehensive documentation for our software products and APIs. You will work closely with development teams to translate complex technical concepts into user-friendly content.</p>

<h3>Key Responsibilities:</h3>
<ul>
  <li>Create and maintain technical documentation, user guides, and API documentation</li>
  <li>Collaborate with developers and product managers to understand features</li>
  <li>Develop tutorials, code examples, and best practice guides</li>
  <li>Review and edit content for clarity, accuracy, and consistency</li>
  <li>Maintain documentation websites and knowledge bases</li>
</ul>

<h3>Requirements:</h3>
<ul>
  <li>2+ years of experience in technical writing</li>
  <li>Strong writing and communication skills</li>
  <li>Basic understanding of software development concepts</li>
  <li>Experience with documentation tools and platforms</li>
  <li>Ability to learn new technologies quickly</li>
</ul>',
                'skills' => ['Technical Writing', 'Git', 'Markdown', 'API Documentation', 'Content Management'],
                'min_salary' => 60000,
                'max_salary' => 85000,
                'payment_period' => 'yearly',
            ],
        ];

        $currentDate = Carbon::now();
        
        // Assign exactly 2 unique jobs to each company
        $jobIndex = 0;
        
        foreach ($companyIds as $companyId) {
            // Each company gets exactly 2 consecutive jobs from the array
            for ($i = 0; $i < 2; $i++) {
                $jobData = $jobDescriptions[$jobIndex];
                
                // Create the job
                JobList::create([
                    'title' => $jobData['title'],
                    'user_id' => $companyId,
                    'location' => User::find($companyId)->location,
                    'date_posted' => $currentDate->copy()->subDays(rand(1, 30))->format('Y-m-d'),
                    'description' => $jobData['description'],
                    'skills' => $jobData['skills'],
                    'min_salary' => $jobData['min_salary'],
                    'max_salary' => $jobData['max_salary'],
                    'salary_negotiable' => (bool)rand(0, 1),
                    'payment_period' => $jobData['payment_period'],
                    'payment_currency' => 'USD',
                    'hiring_multiple_candidates' => (bool)rand(0, 1),
                ]);
                
                $this->command->info("Created job: {$jobData['title']} for company ID: {$companyId}");
                
                $jobIndex++;
            }
        }

        $this->command->info('Created 20 unique jobs (2 per company, no duplicates)');

        // Create 10 users
        $users = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'location' => 'New York, NY',
                'description' => 'Senior software developer with 10+ years of experience in web development. Passionate about creating efficient and scalable applications.',
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Johnson',
                'email' => 'emma.johnson@example.com',
                'image' => 'https://randomuser.me/api/portraits/women/2.jpg',
                'location' => 'San Francisco, CA',
                'description' => 'UX/UI designer with a strong portfolio of user-centered designs. Experienced in creating intuitive interfaces for web and mobile applications.',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Williams',
                'email' => 'michael.williams@example.com',
                'image' => 'https://randomuser.me/api/portraits/men/3.jpg',
                'location' => 'Chicago, IL',
                'description' => 'Full-stack developer specializing in React and Node.js. Passionate about building responsive web applications with clean code.',
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Brown',
                'email' => 'sophia.brown@example.com',
                'image' => 'https://randomuser.me/api/portraits/women/4.jpg',
                'location' => 'Austin, TX',
                'description' => 'Data scientist with expertise in machine learning and predictive modeling. Skilled in Python and data visualization techniques.',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Jones',
                'email' => 'david.jones@example.com',
                'image' => 'https://randomuser.me/api/portraits/men/5.jpg',
                'location' => 'Seattle, WA',
                'description' => 'DevOps engineer with experience in cloud infrastructure and automation. Proficient with AWS, Docker, and Kubernetes.',
            ],
            [
                'first_name' => 'Olivia',
                'last_name' => 'Miller',
                'email' => 'olivia.miller@example.com',
                'image' => 'https://randomuser.me/api/portraits/women/6.jpg',
                'location' => 'Boston, MA',
                'description' => 'Backend developer with expertise in database design and optimization. Skilled in SQL, Java, and microservices architecture.',
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Davis',
                'email' => 'james.davis@example.com',
                'image' => 'https://randomuser.me/api/portraits/men/7.jpg',
                'location' => 'Denver, CO',
                'description' => 'Product manager with a background in software development. Experienced in agile methodologies and user-centered design principles.',
            ],
            [
                'first_name' => 'Ava',
                'last_name' => 'Wilson',
                'email' => 'ava.wilson@example.com',
                'image' => 'https://randomuser.me/api/portraits/women/8.jpg',
                'location' => 'Portland, OR',
                'description' => 'Frontend developer specializing in modern JavaScript frameworks. Passionate about creating accessible and performant web applications.',
            ],
            [
                'first_name' => 'William',
                'last_name' => 'Taylor',
                'email' => 'william.taylor@example.com',
                'image' => 'https://randomuser.me/api/portraits/men/9.jpg',
                'location' => 'Miami, FL',
                'description' => 'Mobile app developer with experience in iOS and Android platforms. Skilled in Swift, Kotlin, and React Native.',
            ],
            [
                'first_name' => 'Isabella',
                'last_name' => 'Anderson',
                'email' => 'isabella.anderson@example.com',
                'image' => 'https://randomuser.me/api/portraits/women/10.jpg',
                'location' => 'Philadelphia, PA',
                'description' => 'QA engineer with a strong focus on automated testing. Experienced in test planning, execution, and continuous integration.',
            ],
        ];

        $userIds = [];

        foreach ($users as $index => $userData) {
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'image_url' => $userData['image'],
                'description' => $userData['description'],
                'location' => $userData['location'],
            ]);
            
            $user->assignRole($userRole);
            $userIds[] = $user->id;
            
            // Assign skills to users based on their profile
            $this->assignSkillsToUser($user, $index);
            
            // Assign job titles to users based on their profile
            $this->assignJobTitlesToUser($user, $index);
            
            $this->command->info("Created user: {$userData['first_name']} {$userData['last_name']}");
        }

        // Make each user apply to 3 jobs
        $allJobs = JobList::all();
        
        foreach ($userIds as $userId) {
            // Select 3 random jobs for this user to apply to
            $randomJobs = $allJobs->random(3);
            
            foreach ($randomJobs as $job) {
                JobApply::create([
                    'job_id' => $job->id,
                    'user_id' => $userId,
                    'proposal' => "I am excited to apply for the {$job->title} position. With my experience and skills, I believe I would be a great fit for this role. I am particularly interested in {$job->user->first_name} and would love the opportunity to contribute to your team.",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Created job applications (3 per user)');
    }
    
    private function assignSkillsToUser(User $user, int $index)
    {
        // Get all available skills from the database
        $allSkills = DB::table('skills')->get();
        
        // Define skill sets based on user profiles
        $skillSets = [
            0 => ['PHP', 'Laravel', 'JavaScript', 'MySQL', 'React'], // John Smith - Senior software developer
            1 => ['UI Design', 'UX Design', 'Figma', 'Adobe XD', 'Sketch', 'Wireframing'], // Emma Johnson - UX/UI designer
            2 => ['JavaScript', 'React', 'Node.js', 'HTML', 'CSS'], // Michael Williams - Full-stack developer
            3 => ['Python', 'Machine Learning', 'Data Analysis', 'Data Visualization', 'Pandas'], // Sophia Brown - Data scientist
            4 => ['Docker', 'AWS', 'Kubernetes', 'CI/CD', 'Terraform'], // David Jones - DevOps engineer
            5 => ['Java', 'SQL', 'PostgreSQL', 'Database Design', 'Microservice Architect'], // Olivia Miller - Backend developer
            6 => ['Product Strategy', 'Agile', 'User Stories', 'Market Research', 'Analytics'], // James Davis - Product manager
            7 => ['JavaScript', 'HTML', 'CSS', 'React', 'Vue.js', 'Angular'], // Ava Wilson - Frontend developer
            8 => ['Swift', 'Kotlin', 'React Native', 'iOS Development', 'Android SDK'], // William Taylor - Mobile app developer
            9 => ['Selenium', 'Cypress', 'API Testing', 'Python', 'JavaScript'], // Isabella Anderson - QA engineer
        ];
        
        // Get skills for this specific user
        $userSkills = $skillSets[$index] ?? ['PHP', 'JavaScript', 'HTML', 'CSS']; // Default skills if index not found
        
        // Assign skills to the user
        foreach ($userSkills as $skillName) {
            $skill = $allSkills->where('name', $skillName)->first();
            if ($skill) {
                DB::table('user_skills')->insert([
                    'user_id' => $user->id,
                    'skill_id' => $skill->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    private function assignJobTitlesToUser(User $user, int $index)
    {
        // Get all available job titles from the database
        $allJobTitles = DB::table('our_jobs_title')->get();
        
        // Define primary job title based on user profiles
        $primaryJobTitles = [
            0 => 'Software Engineer', // John Smith
            1 => 'UX Designer', // Emma Johnson
            2 => 'Full Stack Developer', // Michael Williams
            3 => 'Data Scientist', // Sophia Brown
            4 => 'DevOps Engineer', // David Jones
            5 => 'Backend Developer', // Olivia Miller
            6 => 'Product Manager', // James Davis - Product manager (with technical background)
            7 => 'Frontend Developer', // Ava Wilson
            8 => 'Mobile Developer', // William Taylor
            9 => 'Quality Assurance Engineer', // Isabella Anderson
        ];
        
        // Get job title for this specific user
        $jobTitleName = $primaryJobTitles[$index] ?? 'Web Developer'; // Default job title if index not found
        
        // Assign one job title to the user
        $jobTitle = $allJobTitles->where('name', $jobTitleName)->first();
        if ($jobTitle) {
            DB::table('user_job_title')->insert([
                'user_id' => $user->id,
                'our_jobs_title_id' => $jobTitle->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("  - Assigned job title: {$jobTitleName}");
        } else {
            $this->command->info("  - Warning: Job title '{$jobTitleName}' not found in database");
        }
    }
}
