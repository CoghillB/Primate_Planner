function calculateCaloriesBurned(weight, duration, activityType) {
    const workOutValues = {
        cardio: 7,
        weightlifting: 4,
        hybrid: 5
    };

    const effortValue = workOutValues[activityType.toLowerCase()] || 0;

    const weightInKg = Math.round(weight * 0.453592); // Convert to kg and round to an integer

    return Math.round(effortValue * 0.0175 * weightInKg * duration); // Ensure the result is an integer
}


//Function to update daily exercise

function updateDailyExercise(event) {
    const weight = parseFloat($('#dailyWeight').val());
    const activityType = $('#exerciseChoice').val();
    const duration = parseFloat($('#duration').val());

    const caloriesBurned = calculateCaloriesBurned(weight, duration, activityType);
    $('#calories').text(caloriesBurned);

   // alert(`You burned ${caloriesBurned} calories during this exercise.`);
}

//this will take an array of weights and return the average weight forr the week
function weeklyAverageWeight(weights){
    const totalWeight = weights.reduce((sum, weight) => sum + weight, 0);
    return (totalWeight / weights.length).toFixed(2);
}

//function to keep track of our goals
function trackGoals(currentCalories, weeklyCalories, currentWeight, goalWeight){
    const calorieProgress =((currentCalories / weeklyCalories) * 100).toFixed(2);
    const weightProgress = ((currentWeight / goalWeight) * 100).toFixed(2);
    return{
        calorieProgress,
        weightProgress
    };
}

$(document).ready(function(){
    // Logout button redirects to Login.html
    $('#logoutBTN').on('click', function(){
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
            $('#hiddenCaloriesBurned').val(calories); // Update the hidden input
        } else {
            $('#caloriesBurned').text('0');
            $('#hiddenCaloriesBurned').val('0'); // Ensure hidden input is set to 0
        }
    });

    // Validate form before submitting
    $('#dailySpread').on('submit', function (event) {
        if (!validateExerciseForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});

