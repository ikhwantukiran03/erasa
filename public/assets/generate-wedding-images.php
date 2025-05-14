<?php

// Set headers to allow script execution
header('Content-Type: text/html; charset=utf-8');

// Function to create a wedding background image with decorative elements
function createWeddingBackground($filename, $width, $height, $colors, $style) {
    // Create image
    $image = imagecreatetruecolor($width, $height);
    
    // Enable alpha blending
    imagealphablending($image, true);
    imagesavealpha($image, true);
    
    // Fill with gradient
    $steps = $height;
    
    if ($style === 'gradient') {
        // Create gradient background
        for ($i = 0; $i < $steps; $i++) {
            $r = calculateGradientStep($colors[0][0], $colors[1][0], $steps, $i);
            $g = calculateGradientStep($colors[0][1], $colors[1][1], $steps, $i);
            $b = calculateGradientStep($colors[0][2], $colors[1][2], $steps, $i);
            $color = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $i, $width, $i, $color);
        }
    } elseif ($style === 'solid') {
        // Solid background with patterns
        $bgColor = imagecolorallocate($image, $colors[0][0], $colors[0][1], $colors[0][2]);
        imagefill($image, 0, 0, $bgColor);
    }
    
    // Add decorative elements based on filename
    if (strpos($filename, 'floral') !== false) {
        addFloralElements($image, $width, $height);
    } elseif (strpos($filename, 'rustic') !== false) {
        addRusticElements($image, $width, $height);
    } elseif (strpos($filename, 'gold') !== false) {
        addGoldElements($image, $width, $height);
    } elseif (strpos($filename, 'vintage') !== false) {
        addVintageElements($image, $width, $height);
    } elseif (strpos($filename, 'header') !== false) {
        // For headers, just add some subtle elements
        addHeaderElements($image, $width, $height, $filename);
    }
    
    // Save the image
    imagepng($image, $filename);
    imagedestroy($image);
    
    return $filename;
}

function calculateGradientStep($start, $end, $steps, $current) {
    return $start + (($end - $start) / $steps) * $current;
}

function addFloralElements($image, $width, $height) {
    // Add light floral patterns
    $color = imagecolorallocatealpha($image, 255, 255, 255, 100); // Semi-transparent white
    
    // Draw multiple decorative flower-like patterns
    for ($i = 0; $i < 20; $i++) {
        $x = rand(0, $width);
        $y = rand(0, $height);
        $size = rand(30, 100);
        
        // Draw flower petals
        for ($j = 0; $j < 8; $j++) {
            $angle = $j * 45 * M_PI / 180;
            $x1 = $x + cos($angle) * $size;
            $y1 = $y + sin($angle) * $size;
            imagesetalpha($image, $x1, $y1, 80);
            imageellipse($image, $x1, $y1, $size/2, $size/2, $color);
        }
    }
    
    // Add more elegant curved lines
    for ($i = 0; $i < 8; $i++) {
        $x1 = rand(0, $width);
        $y1 = rand(0, $height);
        $x2 = rand(0, $width);
        $y2 = rand(0, $height);
        
        $control_x = ($x1 + $x2) / 2 + rand(-100, 100);
        $control_y = ($y1 + $y2) / 2 + rand(-100, 100);
        
        imageline($image, $x1, $y1, $control_x, $control_y, $color);
        imageline($image, $control_x, $control_y, $x2, $y2, $color);
    }
}

function addRusticElements($image, $width, $height) {
    // Add rustic wooden-like textures
    $color = imagecolorallocatealpha($image, 139, 69, 19, 100); // Brown with alpha
    
    // Create wood-like lines
    for ($i = 0; $i < $height; $i += rand(5, 15)) {
        $lineColor = imagecolorallocatealpha($image, 139, 69, 19, rand(80, 120));
        imageline($image, 0, $i, $width, $i + rand(-5, 5), $lineColor);
    }
    
    // Add some leaf-like elements
    for ($i = 0; $i < 15; $i++) {
        $x = rand(0, $width);
        $y = rand(0, $height);
        $size = rand(20, 60);
        
        // Simple leaf shape
        $points = [
            $x, $y - $size,
            $x + $size/2, $y,
            $x, $y + $size,
            $x - $size/2, $y
        ];
        
        imagefilledpolygon($image, $points, 4, imagecolorallocatealpha($image, 50, 120, 50, rand(80, 120)));
    }
}

function addGoldElements($image, $width, $height) {
    // Add elegant gold patterns
    $goldColor = imagecolorallocatealpha($image, 212, 175, 55, 90); // Gold with alpha
    
    // Create ornate corner decorations
    $cornerSize = min($width, $height) / 5;
    
    // Top-left corner
    drawOrnateCorner($image, 10, 10, $cornerSize, $goldColor);
    
    // Top-right corner
    drawOrnateCorner($image, $width - 10, 10, $cornerSize, $goldColor, true);
    
    // Bottom-left corner
    drawOrnateCorner($image, 10, $height - 10, $cornerSize, $goldColor, false, true);
    
    // Bottom-right corner
    drawOrnateCorner($image, $width - 10, $height - 10, $cornerSize, $goldColor, true, true);
    
    // Add some sparkles
    for ($i = 0; $i < 30; $i++) {
        $x = rand(0, $width);
        $y = rand(0, $height);
        $size = rand(2, 5);
        
        imagefilledellipse($image, $x, $y, $size, $size, $goldColor);
    }
    
    // Add a border
    imagerectangle($image, 20, 20, $width - 20, $height - 20, $goldColor);
    imagerectangle($image, 22, 22, $width - 22, $height - 22, $goldColor);
}

function drawOrnateCorner($image, $x, $y, $size, $color, $flipX = false, $flipY = false) {
    $xDir = $flipX ? -1 : 1;
    $yDir = $flipY ? -1 : 1;
    
    // Draw curved lines
    for ($i = 0; $i < 3; $i++) {
        $offset = $i * 10;
        imagearc($image, $x, $y, $size - $offset, $size - $offset, 
                 $flipX ? ($flipY ? 0 : 270) : ($flipY ? 90 : 180), 
                 $flipX ? ($flipY ? 90 : 0) : ($flipY ? 180 : 270), 
                 $color);
    }
    
    // Add some decorative dots
    for ($i = 0; $i < 5; $i++) {
        $dotX = $x + $xDir * ($i * $size / 6);
        $dotY = $y + $yDir * ($i * $size / 6);
        imagefilledellipse($image, $dotX, $dotY, 3, 3, $color);
    }
}

function addVintageElements($image, $width, $height) {
    // Add vintage lace-like patterns
    $color = imagecolorallocatealpha($image, 255, 255, 255, 80); // Semi-transparent white
    
    // Create lace border
    $border = 30;
    imagerectangle($image, $border, $border, $width - $border, $height - $border, $color);
    
    // Draw lace pattern inside border
    for ($i = 0; $i < $width; $i += 20) {
        $y1 = $border;
        $y2 = $border + 10;
        imageline($image, $i, $y1, $i + 10, $y2, $color);
        imageline($image, $i + 10, $y2, $i + 20, $y1, $color);
        
        $y1 = $height - $border;
        $y2 = $height - $border - 10;
        imageline($image, $i, $y1, $i + 10, $y2, $color);
        imageline($image, $i + 10, $y2, $i + 20, $y1, $color);
    }
    
    for ($i = 0; $i < $height; $i += 20) {
        $x1 = $border;
        $x2 = $border + 10;
        imageline($image, $x1, $i, $x2, $i + 10, $color);
        imageline($image, $x2, $i + 10, $x1, $i + 20, $color);
        
        $x1 = $width - $border;
        $x2 = $width - $border - 10;
        imageline($image, $x1, $i, $x2, $i + 10, $color);
        imageline($image, $x2, $i + 10, $x1, $i + 20, $color);
    }
    
    // Add some vintage swirls
    for ($i = 0; $i < 10; $i++) {
        $x = rand($border * 2, $width - $border * 2);
        $y = rand($border * 2, $height - $border * 2);
        $size = rand(20, 40);
        
        imagearc($image, $x, $y, $size, $size, 0, 300, $color);
        imagearc($image, $x, $y, $size - 5, $size - 5, 0, 320, $color);
    }
}

function addHeaderElements($image, $width, $height, $filename) {
    // Extract template number
    $templateNum = 1;
    if (preg_match('/(\d+)-header/', $filename, $matches)) {
        $templateNum = (int)$matches[1];
    }
    
    $color = imagecolorallocatealpha($image, 255, 255, 255, 70);
    
    switch ($templateNum) {
        case 1: // Floral header
            // Add some floral elements
            for ($i = 0; $i < 10; $i++) {
                $x = rand(0, $width);
                $y = rand(0, $height);
                $size = rand(10, 30);
                imagefilledellipse($image, $x, $y, $size, $size, $color);
                
                // Draw simple flower
                for ($j = 0; $j < 5; $j++) {
                    $angle = $j * 72 * M_PI / 180;
                    $x1 = $x + cos($angle) * $size;
                    $y1 = $y + sin($angle) * $size;
                    imagefilledellipse($image, $x1, $y1, $size/2, $size/2, $color);
                }
            }
            break;
            
        case 3: // Rustic header
            // Add rustic elements like branches
            for ($i = 0; $i < $width; $i += 20) {
                imageline($image, $i, rand(0, $height), $i + rand(-20, 20), rand(0, $height), $color);
            }
            break;
            
        case 5: // Vintage header
            // Add lace-like pattern at the top
            for ($i = 0; $i < $width; $i += 15) {
                $y1 = 5;
                $y2 = 15;
                imagearc($image, $i, $y1, 15, 15, 0, 180, $color);
                imagearc($image, $i + 7.5, $y2, 15, 15, 180, 0, $color);
            }
            break;
    }
}

// Define the background images to generate
$backgrounds = [
    [
        'filename' => 'public/assets/wedding-backgrounds/floral-bg.jpg',
        'width' => 1200,
        'height' => 800,
        'colors' => [
            [240, 248, 255], // Light Blue/White
            [230, 230, 250]  // Lavender
        ],
        'style' => 'gradient'
    ],
    [
        'filename' => 'public/assets/wedding-backgrounds/rustic-bg.jpg',
        'width' => 1200,
        'height' => 800,
        'colors' => [
            [245, 222, 179], // Wheat
            [222, 184, 135]  // Burlap
        ],
        'style' => 'gradient'
    ],
    [
        'filename' => 'public/assets/wedding-backgrounds/gold-bg.jpg',
        'width' => 1200,
        'height' => 800,
        'colors' => [
            [255, 248, 220], // Cornsilk
            [238, 232, 170]  // Pale Goldenrod
        ],
        'style' => 'gradient'
    ],
    [
        'filename' => 'public/assets/wedding-backgrounds/vintage-bg.jpg',
        'width' => 1200,
        'height' => 800,
        'colors' => [
            [255, 240, 245], // Lavender Blush
            [255, 228, 225]  // Misty Rose
        ],
        'style' => 'gradient'
    ],
];

// Define template headers to generate
$headers = [
    [
        'filename' => 'public/assets/wedding-templates/1-header.jpg',
        'width' => 1200,
        'height' => 300,
        'colors' => [
            [221, 160, 221], // Plum
            [240, 230, 140]  // Khaki
        ],
        'style' => 'gradient'
    ],
    [
        'filename' => 'public/assets/wedding-templates/3-header.jpg',
        'width' => 1200,
        'height' => 300,
        'colors' => [
            [205, 133, 63], // Peru (brown)
            [222, 184, 135]  // Burlap
        ],
        'style' => 'gradient'
    ],
    [
        'filename' => 'public/assets/wedding-templates/5-header.jpg',
        'width' => 1200,
        'height' => 200,
        'colors' => [
            [230, 230, 250], // Lavender
            [255, 248, 220]  // Cornsilk
        ],
        'style' => 'gradient'
    ],
    [
        'filename' => 'public/assets/wedding-templates/4-bg.jpg',
        'width' => 1200,
        'height' => 800,
        'colors' => [
            [255, 250, 240], // Floral White
            [255, 248, 220]  // Cornsilk
        ],
        'style' => 'gradient'
    ],
];

// Template images
$templates = [];
for ($i = 1; $i <= 5; $i++) {
    $templates[] = [
        'filename' => "public/assets/wedding-templates/{$i}.jpg",
        'width' => 400,
        'height' => 300,
        'colors' => [
            [255, 255, 255], // White
            [240, 240, 240]  // Light Gray
        ],
        'style' => 'solid'
    ];
}

// Generate all images
$generatedFiles = [];

echo '<h1>Generating Wedding Background Images...</h1>';
echo '<ul>';

// Generate backgrounds
foreach ($backgrounds as $bg) {
    $file = createWeddingBackground(
        $bg['filename'],
        $bg['width'],
        $bg['height'],
        $bg['colors'],
        $bg['style']
    );
    $generatedFiles[] = $file;
    echo '<li>Generated: ' . $file . '</li>';
}

// Generate headers
foreach ($headers as $header) {
    $file = createWeddingBackground(
        $header['filename'],
        $header['width'],
        $header['height'],
        $header['colors'],
        $header['style']
    );
    $generatedFiles[] = $file;
    echo '<li>Generated: ' . $file . '</li>';
}

// Generate template thumbnails
foreach ($templates as $template) {
    $file = createWeddingBackground(
        $template['filename'],
        $template['width'],
        $template['height'],
        $template['colors'],
        $template['style']
    );
    $generatedFiles[] = $file;
    echo '<li>Generated: ' . $file . '</li>';
}

echo '</ul>';
echo '<h2>All ' . count($generatedFiles) . ' images have been generated successfully!</h2>';
echo '<p>You can now delete this script or keep it for future updates.</p>';
echo '<p>The images are ready to be used in your wedding card templates.</p>';
?> 