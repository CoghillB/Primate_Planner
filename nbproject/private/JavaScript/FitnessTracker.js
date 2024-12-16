function calculateCaloriesBurned(weight, duration, activityType) {
    const workOutValues = {
        cardio: 7.0,
        weightlifting: 4.0,
        hybrid: 5.5
    };

    const effortValue = workOutValues[activityType.toLowerCase()] || 0;

    const weightInKg = weight * 0.453592;

    return (effortValue * 0.0175 * weightInKg * duration).toFixed(2);
}

//Function to update daily exercise

function updateDailyExercise(event) {
    const weight = parseFloat($('#dailyWeight').val());
    const activityType = $('#exerciseChoice').val();
    const duration = parseFloat($('#duration').val());

    const caloriesBurned = calculateCaloriesBurned(weight, duration, activityType);
    $('#caloriesBurned').text(caloriesBurned);
}

//this will take an array of weights and return the average weight for the week
function weeklyAverageWeight(weights) {
    const totalWeight = weights.reduce((sum, weight) => sum + weight, 0);
    return (totalWeight / weights.length).toFixed(2);
}

//function to keep track of our goals
function trackGoals(currentCalories, weeklyCalories, currentWeight, goalWeight) {
    const calorieProgress = ((currentCalories / weeklyCalories) * 100).toFixed(2);
    const weightProgress = ((currentWeight / goalWeight) * 100).toFixed(2);
    return {
        calorieProgress,
        weightProgress
    };
}

$(document).ready(function () {
    // Logout button redirects to Login.html
    $('#logoutBTN').on('click', function () {
        // Redirect to login page
        window.location.href = 'Login.html';
    });

    //toggle between goals and exercise
    $('#goals').hide();
    $('#exercises').hide();

    // Toggle between goals and exercises
    $('#showGoals').on('click', function () {
        $('#goals').show();
        $('#exercises').hide();
    });

    $('#showExercises').on('click', function () {
        $('#exercises').show();
        $('#goals').hide();
    });

    // Real-time updates for calories burned
    $('#duration, #exerciseChoice, #dailyWeight').on('input change', function () {
        const weight = parseFloat($('#dailyWeight').val()) || 0;
        const duration = parseFloat($('#duration').val()) || 0;
        const activityType = $('#exerciseChoice').val();

        if (weight > 0 && duration > 0) {
            const calories = calculateCaloriesBurned(weight, duration, activityType);
            $('#caloriesBurned').text(calories);
        } else {
            $('#caloriesBurned').text('0');
        }
    });
    // Validate form before submitting
    $('#dailySpread').on('submit', function (event) {
        if (!validateExerciseForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});