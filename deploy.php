<?php

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define your GitHub repository information
   $githubUser = 'Hamzaqureshi401';
        $githubRepo = 'scoops-gradient-lte-project';
        $githubToken = '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';

    // Change the working directory to the root of your project (public_html)
    chdir(__DIR__);

    // Execute the Git pull command
    $output = shell_exec("git pull origin master");

    // Check if the Git command was executed successfully
    if ($output !== null) {
        // Process the output or perform other tasks if needed
        // For example, you can log the output for debugging purposes
        file_put_contents('git_log.txt', $output);

        // Respond with a success message
        http_response_code(200);
        echo json_encode([
            'code' => 200,
            'status' => 'success',
            'message' => 'Code updated successfully!',
        ]);
    } else {
        // Respond with an error message
        http_response_code(500);
        echo json_encode([
            'code' => 500,
            'status' => 'error',
            'message' => 'Failed to update code.',
        ]);
    }
} else {
    // Respond with a method not allowed message
    http_response_code(405);
    echo json_encode([
        'code' => 405,
        'status' => 'error',
        'message' => 'Method Not Allowed.',
    ]);
}
