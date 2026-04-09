protected $routeMiddleware = [
    // ... other middleware
    'instructor' => \App\Http\Middleware\InstructorMiddleware::class,
];