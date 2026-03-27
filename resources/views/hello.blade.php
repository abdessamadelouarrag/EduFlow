<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Liste des cours</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            color: #1e293b;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 0;
        }

        .hero {
            text-align: center;
            margin-bottom: 40px;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 12px;
            color: #0f172a;
        }

        .hero p {
            font-size: 1rem;
            color: #475569;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            padding: 18px 20px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .badge {
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .status {
            color: #475569;
            font-size: 0.95rem;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .course-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .course-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        }

        .course-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #2563eb, #7c3aed);
        }

        .course-domain {
            display: inline-block;
            margin-bottom: 14px;
            background: #eff6ff;
            color: #2563eb;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .course-card h3 {
            font-size: 1.25rem;
            margin-bottom: 12px;
            color: #0f172a;
        }

        .course-description {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 20px;
            min-height: 72px;
        }

        .course-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .price {
            font-size: 1.1rem;
            font-weight: bold;
            color: #16a34a;
        }

        .btn {
            border: none;
            background: linear-gradient(90deg, #2563eb, #7c3aed);
            color: white;
            padding: 10px 16px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            transition: opacity 0.2s ease;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .loading,
        .error,
        .empty {
            text-align: center;
            padding: 30px;
            border-radius: 16px;
            margin-top: 20px;
            font-size: 1rem;
        }

        .loading {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .error {
            background: #fee2e2;
            color: #dc2626;
        }

        .empty {
            background: #f1f5f9;
            color: #475569;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .course-description {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <section class="hero">
            <h1>Liste des cours</h1>
            <p>
                Découvrez les formations disponibles, explorez les domaines qui vous intéressent
                et choisissez le cours qui correspond à vos objectifs.
            </p>
        </section>

        <div class="top-bar">
            <span class="badge">Catalogue des formations</span>
            <span class="status" id="course-count">Chargement des cours...</span>
        </div>

        <div id="message" class="loading">Chargement des cours...</div>
        <div id="courses" class="courses-grid"></div>
    </div>

    <script>
        const coursesDiv = document.getElementById('courses');
        const messageDiv = document.getElementById('message');
        const courseCount = document.getElementById('course-count');

        fetch('http://127.0.0.1:8006/api/courses')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Impossible de récupérer les cours');
                }
                return response.json();
            })
            .then(data => {
                const courses = data.data ? data.data : data;

                messageDiv.style.display = 'none';

                if (!courses || courses.length === 0) {
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'empty';
                    messageDiv.textContent = 'Aucun cours disponible pour le moment.';
                    courseCount.textContent = '0 cours trouvés';
                    return;
                }

                courseCount.textContent = `${courses.length} cours trouvés`;

                courses.forEach(course => {
                    const card = document.createElement('div');
                    card.className = 'course-card';

                    card.innerHTML = `
                        <span class="course-domain">${course.domain ?? 'Non défini'}</span>
                        <h3>${course.title ?? 'Sans titre'}</h3>
                        <p class="course-description">${course.description ?? 'Aucune description disponible.'}</p>
                        <div class="course-footer">
                            <span class="price">${course.price ?? 0} MAD</span>
                            <button class="btn">Voir plus</button>
                        </div>
                    `;

                    coursesDiv.appendChild(card);
                });
            })
            .catch(error => {
                messageDiv.style.display = 'block';
                messageDiv.className = 'error';
                messageDiv.textContent = 'Erreur : ' + error.message;
                courseCount.textContent = 'Erreur de chargement';
                console.error(error);
            });
    </script>
</body>
</html>