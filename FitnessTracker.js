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

    // Validate inputs
    if (!weight || weight <= 0) {
       // alert("Please enter a valid weight to calculate calories burned.");
        return;
    }

    if (!duration || duration <= 0) {
       // alert("Please enter a valid duration for your exercise.");
        return;
    }

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
        const weight = parseFloat($('#dailyWeight').val()) || 0; // Use dailyWeight for real-time calculation
        const duration = parseFloat($('#duration').val()) || 0;
        const activityType = $('#exerciseChoice').val();

        if (weight > 0 && duration > 0) {
            const calories = calculateCaloriesBurned(weight, duration, activityType);
            $('#caloriesBurned').text(calories);
        } else {
            $('#caloriesBurned').text('0');
        }
    });
    $('#dailySpread').on('submit', updateDailyExercise);
});