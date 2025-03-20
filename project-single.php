<?php
    include('Includes/header.php');
?>

            <?php
            include "config.php";

            // Get the project ID from the URL
            $project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            if ($project_id > 0) {
                // Use prepared statement to prevent SQL injection
                $stmt = $conn->prepare("
                    SELECT 
                        p.project_id, 
                        p.project_name, 
                        p.description,
                        p.project_type,
                        p.client,
                        p.start_date,
                        p.end_date,
                        GROUP_CONCAT(DISTINCT i.image_path ORDER BY i.image_id ASC) AS images,
                        GROUP_CONCAT(DISTINCT t.technology_name ORDER BY t.technology_id ASC) AS technologies
                    FROM Projects p
                    LEFT JOIN Images i ON p.project_id = i.project_id
                    LEFT JOIN ProjectTechnologies pt ON p.project_id = pt.project_id
                    LEFT JOIN Technologies t ON pt.technology_id = t.technology_id
                    WHERE p.project_id = ?
                    GROUP BY p.project_id
                ");
                $stmt->bind_param("i", $project_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Output data of each row
                    $project = $result->fetch_assoc();

                    $images = explode(',', $project['images']);
                    $technologies = explode(',', $project['technologies']);

                    // Calculate duration in hours
                    $start_date = new DateTime($project['start_date']);
                    $end_date = new DateTime($project['end_date']);
                    $interval = $start_date->diff($end_date);
                    $hours = ($interval->days * 24) + $interval->h + ($interval->i / 60);
                } else {
                    echo "No project found.";
                    exit;
                }
            } else {
                echo "Invalid project ID.";
                exit;
            }
            $stmt->close();
            $conn->close();
            ?>

            <!-- Main Content Start -->
            <div class="relative mx-auto minfo__contentBox max-w-container xl:max-2xl:max-w-65rem">

                <!-- Project Details Section Start -->
                <div class="py-3.5 max-w-content xl:max-2xl:max-w-50rem max-xl:mx-auto xl:ml-auto">

                    <div class="px-5 py-8 md:p-8 bg-white dark:bg-nightBlack rounded-2xl lg:p-10 2xl:p-13">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs tracking-wide text-black dark:text-white border lg:px-5 section-name border-platinum dark:border-greyBlack200 rounded-4xl">
                            <i class="fal fa-tasks-alt text-theme"></i>
                            Project Details
                        </div>

                        <h2
                            class="text-2xl font-semibold leading-normal text-black dark:text-white mt-7 lg:mt-10 article-title lg:text-3xl lg:leading-normal">
                            <?php echo htmlspecialchars($project['project_name']); ?>
                        </h2>
                        <div class="mb-4 overflow-hidden mt-7 xl:my-8 thumb rounded-xl xl:rounded-2xl">
                            <img src="<?php echo htmlspecialchars($images[0]); ?>" class="w-full"
                                alt="Project Thumbnail Image">
                        </div>
                        <div class="post-meta sm:flex items-center justify-between my-8 mb-10 max-sm:space-y-3.5">
                            <div>
                                <h6 class="text-black dark:text-white">CLIENT</h6>
                                <p class="text-regular"><?php echo htmlspecialchars($project['client']); ?></p>
                            </div>
                            <div>
                                <h6 class="text-black dark:text-white">DURATION</h6>
                                <p class="text-regular"><?php echo intval($hours) . ' hours'; ?></p>
                            </div>
                            <div>
                                <h6 class="text-black dark:text-white">TECHNOLOGIES</h6>
                                <p class="text-regular"><?php echo htmlspecialchars(implode(', ', $technologies)); ?></p>
                            </div>
                        </div>


                        <div>
                            <h3 class="mb-3 text-lg font-medium text-black dark:text-white xl:text-2xl">Project Description</h3>
                            <p class="text-regular !leading-[2]">
                                <?php echo htmlspecialchars($project['description']); ?>
                            </p>
                            <ul class="text-regular !leading-[2] list-disc ml-6 my-4">
                                <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do</li>
                                <li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris</li>
                                <li>Excepteur sint occaecat cupidatat non proident, sunt in culpa</li>
                            </ul>

                            <div class="grid gap-5 my-8 sm:grid-cols-2 md:gap-8">
                                <div class="overflow-hidden rounded-xl xl:rounded-2xl">
                                    <img src="assets/img/portfolio/portfolio-img1-2.png" class="w-full"
                                        alt="Project Inner Colum Image">
                                </div>
                                <div class="overflow-hidden rounded-xl xl:rounded-2xl">
                                    <img src="assets/img/portfolio/portfolio-img1-1.png" class="w-full"
                                        alt="Project Inner Colum Image">
                                </div>
                            </div>

                            <h3 class="mt-12 mb-10 text-2xl font-medium text-black dark:text-white">Technologies</h3>
                            <div class="progressbar-wrap space-y-7">
                                <div class="flex flex-wrap items-center gap-5 progressbar">
                                    <div class="w-8 icon">
                                        <img src="assets/img/skill/html.svg" alt="HTML5">
                                    </div>
                                    <div class="flex-1 bar" data-percentage="90%">
                                        <h5 class="mb-2 text-black dark:text-white progress-title-holder text-regular">
                                            <span class="progress-title">HTML5</span>
                                        </h5>
                                        <div class="progress-outer bg-platinum dark:bg-greyBlack h-1.5 rounded-2xl">
                                            <div class="progress-content bg-theme h-1.5 w-0 rounded-2xl"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-5 progressbar">
                                    <div class="w-8 icon">
                                        <img src="assets/img/skill/css.svg" alt="CSS3">
                                    </div>
                                    <div class="flex-1 bar" data-percentage="80%">
                                        <h5 class="mb-2 text-black dark:text-white progress-title-holder text-regular">
                                            <span class="progress-title">CSS3</span>
                                        </h5>
                                        <div class="progress-outer bg-platinum dark:bg-greyBlack h-1.5 rounded-2xl">
                                            <div class="progress-content bg-theme h-1.5 w-0 rounded-2xl"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-5 progressbar">
                                    <div class="w-8 icon">
                                        <img src="assets/img/skill/js.svg" alt="javascript">
                                    </div>
                                    <div class="flex-1 bar" data-percentage="60%">
                                        <h5 class="mb-2 text-black dark:text-white progress-title-holder text-regular">
                                            <span class="progress-title">javascript</span>
                                        </h5>
                                        <div class="progress-outer bg-platinum dark:bg-greyBlack h-1.5 rounded-2xl">
                                            <div class="progress-content bg-theme h-1.5 w-0 rounded-2xl"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-5 progressbar">
                                    <div class="w-8 icon">
                                        <img src="assets/img/skill/tailwind.svg" alt="TailwindCSS">
                                    </div>
                                    <div class="flex-1 bar" data-percentage="90%">
                                        <h5 class="mb-2 text-black dark:text-white progress-title-holder text-regular">
                                            <span class="progress-title">TailwindCSS</span>
                                        </h5>
                                        <div class="progress-outer bg-platinum dark:bg-greyBlack h-1.5 rounded-2xl">
                                            <div class="progress-content bg-theme h-1.5 w-0 rounded-2xl"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-5 progressbar">
                                    <div class="w-8 icon">
                                        <img src="assets/img/skill/figma.svg" alt="TailwindCSS">
                                    </div>
                                    <div class="flex-1 bar" data-percentage="80%">
                                        <h5 class="mb-2 text-black dark:text-white progress-title-holder text-regular">
                                            <span class="progress-title">Figma</span>
                                        </h5>
                                        <div class="progress-outer bg-platinum dark:bg-greyBlack h-1.5 rounded-2xl">
                                            <div class="progress-content bg-theme h-1.5 w-0 rounded-2xl"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Project Details Section End -->


<?php
    include('Includes/footer.php');
?>